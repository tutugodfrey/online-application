#!/usr/bin/php

<?php

    $cobrandName = null;

    $skip = 0;

    foreach ($argv as $arg) {
        if ($skip == 0) {
            $skip++;
            continue;
        }

        $array = preg_split('/-/', $arg);
        $key = $array[0];
        $val = $array[1];

        if ($key == 'cobrand_name') {
            $cobrandName = $val;
        }
    }

    if ($cobrandName == null) {
        print "\n";
        print "missing required argument:\n";
        print "\tadd_optblue_changes_to_template.php cobrand_name-TheCobrand'\n\n";
        exit;
    }

    $file = "/tmp/add_optblue_changes_to_template.txt"; 
    $filehandle = fopen($file, 'w');

    $conn_string = 'host=localhost port=5432 dbname=axia_legacy user=axia password=ax!a';
    $conn = pg_connect($conn_string);

    if ($conn) {
        fwrite($filehandle, "successfully connected to db: $conn_string\n");
    }
    else {
        fwrite($filehandle, "could not connect to db: $conn_string\n");
    }

    $GUPflag = true;

    $cobrandId = null;

    $cobrandIdQuery = pg_query($conn, "SELECT * FROM onlineapp_cobrands WHERE partner_name = '$cobrandName'");

    if ($row = pg_fetch_assoc($cobrandIdQuery)) {
        $cobrandId = $row['id'];
    }

    $templateQuery = pg_query($conn, "SELECT * FROM onlineapp_templates WHERE cobrand_id = ".$cobrandId);

    while ($templateRow = pg_fetch_assoc($templateQuery)) {
        $newTemplateId = createTemplate($cobrandId, $templateRow);

        $pageQuery = pg_query($conn, "SELECT * FROM onlineapp_template_pages WHERE template_id = ".$templateRow['id']);

        while ($pageRow = pg_fetch_assoc($pageQuery)) {
            $newPageId = createTemplatePage($newTemplateId, $pageRow);

            $sectionQuery = pg_query($conn, "SELECT * FROM onlineapp_template_sections WHERE page_id = ".$pageRow['id']);

            while ($sectionRow = pg_fetch_assoc($sectionQuery)) {
                $newSectionId = createTemplateSection($newPageId, $sectionRow);

                $fieldQuery = pg_query($conn, "SELECT * FROM onlineapp_template_fields WHERE section_id = ".$sectionRow['id']);

                while ($fieldRow = pg_fetch_assoc($fieldQuery)) {


			if ($sectionRow['name'] == 'General Underwriting Profile') {
			    if ($GUPflag == true) {
			        $DiscVol = array(
				    'id' => 0,
				    'name' => 'Discover Volume',
				    'description' => '',
				    'rep_only' => 'f',
				    'width' => 4,
				    'type' => 10,
				    'required' => 't',
				    'source' => 2,
				    'default_value' => '',
				    'merge_field_name' => 'DiscVol',
				    'order' => '5',
				    'encrypt' => 'f'
			        );

                                $result = createTemplateField($newSectionId, $DiscVol);

			        $AEMoVol = array(
				    'id' => 0,
				    'name' => 'American Express Volume',
				    'description' => '',
				    'rep_only' => 'f',
				    'width' => 4,
				    'type' => 10,
				    'required' => 't',
				    'source' => 2,
				    'default_value' => '',
				    'merge_field_name' => 'AEMoVol',
				    'order' => '6',
				    'encrypt' => 'f'
			        );

                                $result = createTemplateField($newSectionId, $AEMoVol);

			        $PINMoVol = array(
				    'id' => 0,
				    'name' => 'Pin Debit Volume',
				    'description' => '',
				    'rep_only' => 'f',
				    'width' => 4,
				    'type' => 10,
				    'required' => 't',
				    'source' => 2,
				    'default_value' => '',
				    'merge_field_name' => 'PINMoVol',
				    'order' => '7',
				    'encrypt' => 'f'
			        );

                                $result = createTemplateField($newSectionId, $PINMoVol);

				$GUPflag = false;
			    }
			}

                    $newFieldId = createTemplateField($newSectionId, $fieldRow);
                }
            }
        }
    }

    fclose($filehandle);

    function createTemplate($id, $data) {
        global $conn;

        $newTemplateQuery = "
            INSERT INTO onlineapp_templates (
                name,
                logo_position,
                include_brand_logo,
                description,
                cobrand_id,
                created,
                modified,
                rightsignature_template_guid,
                rightsignature_install_template_guid,
		owner_equity_threshold
            )
            VALUES (
                '$data[name]',
                '$data[logo_position]',
                '$data[include_brand_logo]',
                '$data[description]',
                $id,
                now(),
                now(),
                '$data[rightsignature_template_guid]',
                '$data[rightsignature_install_template_guid]',
                '$data[owner_equity_threshold]'
            )
            RETURNING Currval('onlineapp_templates_id_seq')
        ";

        $newTemplateResult = pg_query($conn, $newTemplateQuery);
        $row = pg_fetch_row($newTemplateResult);

	$newName = $data['name'].' - pre opt-blue';
	$updateTemplateNameQuery = "
		UPDATE onlineapp_templates
		   SET name = '".$newName."',
		       modified = now()
		 WHERE id = ".$data['id'];
	$updateResult = pg_query($conn, $updateTemplateNameQuery);

        return $row[0];
    }

    function createTemplatePage($id, $data) {
        global $conn;

        $newTemplatePageQuery = "
            INSERT INTO onlineapp_template_pages (
                name,
                description,
                rep_only,
                template_id,
                \"order\",
                created,
                modified
            )
            VALUES (
                '$data[name]',
                '$data[description]',
                '$data[rep_only]',
                $id,
                $data[order],
                now(),
                now()
            )
            RETURNING Currval('onlineapp_template_pages_id_seq')
        ";

        $newTemplatePageResult = pg_query($conn, $newTemplatePageQuery);
        $row = pg_fetch_row($newTemplatePageResult);
        return $row[0];
    }

    function createTemplateSection($id, $data) {
        global $conn;

        $newTemplateSectionQuery = "
            INSERT INTO onlineapp_template_sections (
                name,
                description,
                rep_only,
                width,
                page_id,
                \"order\",
                created,
                modified
            )
            VALUES (
                '$data[name]',
                '$data[description]',
                '$data[rep_only]',
                $data[width],
                $id,
                $data[order],
                now(),
                now()
            )
            RETURNING Currval('onlineapp_template_sections_id_seq')
        ";

        $newTemplateSectionResult = pg_query($conn, $newTemplateSectionQuery);
        $row = pg_fetch_row($newTemplateSectionResult);

        return $row[0];
    }

    function createTemplateField($id, $data) {
        global $conn;

        $newTemplateFieldQuery = "
            INSERT INTO onlineapp_template_fields (
                name,
                description,
                rep_only,
                width,
                type,
                required,
                source,
                default_value,
                merge_field_name,
                \"order\",
                section_id,
                created,
                modified,
                encrypt
            )
            VALUES (
                '$data[name]',
                '$data[description]',
                '$data[rep_only]',
                $data[width],
                $data[type],
                '$data[required]',
                $data[source],
                '$data[default_value]',
                '$data[merge_field_name]',
                $data[order],
                $id,
                now(),
                now(),
                '$data[encrypt]'
            )
            RETURNING Currval('onlineapp_template_fields_id_seq')
        ";

        $newTemplateFieldResult = pg_query($conn, $newTemplateFieldQuery);
        $row = pg_fetch_row($newTemplateFieldResult);
        return $row[0];
    }

?>
