<?php
    class SqlExecHelper {

        public static function filterSqlVerbs($raw_input) {
            $sql_verbs = [];
            $sql_verbs[] = "CREATE";
            $sql_verbs[] = "ALTER";
            $sql_verbs[] = "DROP";
            $sql_verbs[] = "TRUNCATE";
            $sql_verbs[] = "RENAME ";
            $sql_verbs[] = "COMMENT"; 
            $sql_verbs[] = "INSERT";
            $sql_verbs[] = "UPDATE";
            $sql_verbs[] = "DELETE";
            $sql_verbs[] = "COMMIT";
            $sql_verbs[] = "ROLLBACK";
            $sql_verbs[] = "SAVEPOINT";
            $sql_verbs[] = "TRANSACTION";
            $sql_verbs[] = "GRANT";
            $sql_verbs[] = "REVOKE";
            $sql_verbs[] = "SELECT";
            $sql_verbs[] = "CALL ";
            $sql_verbs[] = "EXECUTE ";

            // Filter out SQL verbs
            return str_replace($sql_verbs, "", $raw_input);
        }
    }
?>