grep -irl "\?>" vendors/ controllers/ models/ config/ locale/ | grep -v "config/schema/schema.php" | grep -v "config/acl.ini.php"
