BEGIN;

UPDATE onlineapp_coversheets
   SET cobranded_application_id = null
 WHERE cobranded_application_id < 5000;

UPDATE onlineapp_email_timelines
   SET cobranded_application_id = null
 WHERE cobranded_application_id < 5000;

DELETE FROM onlineapp_cobranded_application_values
      WHERE cobranded_application_id < 5000;

DELETE FROM onlineapp_cobranded_applications
      WHERE id < 5000;

END;
