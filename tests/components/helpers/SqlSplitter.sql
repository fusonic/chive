CREATE PROCEDURE simpleproc (OUT param1 INT)
    BEGIN
       SELECT COUNT(*) INTO param1 FROM t;
    END;
//

CREATE PROCEDURE simpleproc2 (OUT param1 INT)
    BEGIN
       SELECT 'asdf' INTO param1 FROM `test`;	-- this is just a test //
    END;
//


