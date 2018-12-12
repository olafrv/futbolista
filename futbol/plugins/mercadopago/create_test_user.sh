#!/bin/sh

# Cada llamada a la API crea un usuario de prueba. Utiliza el dato site_id para indicar el país donde quieres realizar las pruebas. Argentina: MLA, Brasil: MLB, México: MLM, Venezuela: MLV y Colombia: MCO.

curl -X POST \
-H "Content-Type: application/json" \
"https://api.mercadolibre.com/users/test_user?access_token=$1" \
-d "{'site_id':'MLV'}"

# Seller
#{"id":151654670,"nickname":"TETE8219124","password":"qatest4587","site_status":"active","email":"test_user_41266808@testuser.com"}
# Client_id:	8136045856941180
# Client_secret:	muSh7IfwnkfWyiriISK0YyHJDc7sdY89
# Buyer
#{"id":151654685,"nickname":"TETE8079024","password":"qatest7032","site_status":"active","email":"test_user_28037902@testuser.com"}
