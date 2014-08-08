#!/usr/bin/php

<?php

    $file = "/tmp/template_copier.txt"; 
    $filehandle = fopen($file, 'w');

  //  $cobrandId = $argv[1];
//DEBUG
$cobrandId = 4;
 //   $fromDb = $argv[2];
 //   $toDb = $argv[3];

    $from_conn_string = "host=localhost port=5432 dbname=axia_legacy user=axia password=ax!a";
    $from_conn = pg_connect($from_conn_string);

    if ($from_conn) {
        fwrite($filehandle, "successfully connected to db: $from_conn_string\n");
    }

    $to_conn_string = "host=localhost port=5432 dbname=axia_legacy user=axia password=ax!a";
    $to_conn = pg_connect($to_conn_string);

    if ($to_conn) {
        fwrite($filehandle, "successfully connected to db: $to_conn_string\n");
    }

    $templateQuery = pg_query($from_conn, "SELECT * FROM onlineapp_templates WHERE cobrand_id = ".$cobrandId);

    while ($templateRow = pg_fetch_assoc($templateQuery)) {
        $newTemplateId = createTemplate($templateRow);

        $pageQuery = pg_query($from_conn, "SELECT * FROM onlineapp_template_pages WHERE template_id = ".$templateRow['id']);

        while ($pageRow = pg_fetch_assoc($pageQuery)) {
            $newPageId = createTemplatePage($newTemplateId, $pageRow);

            $sectionQuery = pg_query($from_conn, "SELECT * FROM onlineapp_template_sections WHERE page_id = ".$pageRow['id']);

            while ($sectionRow = pg_fetch_assoc($sectionQuery)) {
                $newSectionId = createTemplateSection($newPageId, $sectionRow);

                $fieldQuery = pg_query($from_conn, "SELECT * FROM onlineapp_template_fields WHERE section_id = ".$sectionRow['id']);

                while ($fieldRow = pg_fetch_assoc($fieldQuery)) {
                    $newFieldId = createTemplateField($newSectionId, $fieldRow);
                }
            }
        }
    }

    fclose($filehandle);

    function createTemplate($data) {
        global $to_conn;

        $newTemplateQuery = "
            INSERT INTO onlineapp_templates (
                name,
                logo_position,
                include_axia_logo,
                description,
                cobrand_id,
                created,
                modified,
                rightsignature_template_guid,
                rightsignature_install_template_guid
            )
            VALUES (
                '$data[name]',
                '$data[logo_position]',
                '$data[include_axia_logo]',
                '$data[description]',
                $data[cobrand_id],
                now(),
                now(),
                '$data[rightsignature_template_guid]',
                '$data[rightsignature_install_template_guid]'
            )
            RETURNING Currval('onlineapp_templates_id_seq')
        ";

        $newTemplateResult = pg_query($to_conn, $newTemplateQuery);
        $row = pg_fetch_row($newTemplateResult);
        return $row[0];
    }

    function createTemplatePage($id, $data) {
        global $to_conn;

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

        $newTemplatePageResult = pg_query($to_conn, $newTemplatePageQuery);
        $row = pg_fetch_row($newTemplatePageResult);
        return $row[0];
    }

    function createTemplateSection($id, $data) {
        global $to_conn;

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

        $newTemplateSectionResult = pg_query($to_conn, $newTemplateSectionQuery);
        $row = pg_fetch_row($newTemplateSectionResult);

        return $row[0];
    }

    function createTemplateField($id, $data) {
        global $to_conn;

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

        $newTemplateFieldResult = pg_query($to_conn, $newTemplateFieldQuery);
        $row = pg_fetch_row($newTemplateFieldResult);
        return $row[0];
    }

?>
