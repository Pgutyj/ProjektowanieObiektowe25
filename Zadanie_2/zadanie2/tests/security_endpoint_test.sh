#!/bin/bash


echo "🟢 Test: GET /login"
curl -i -X GET http://localhost:8000/login
echo -e "\n-----------------------------------\n"


echo "🟢 Test: POST /login (złe dane)"
curl -i -X POST http://localhost:8000/login \
  -d "_username=wronguser" \
  -d "_password=wrongpass"
echo -e "\n-----------------------------------\n"


echo "🟢 Test: POST /register"
curl -i -X POST http://localhost:8000/register \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "registration_form[username]=testuser" \
  -d "registration_form[email]=bashtest@example.com" \
  -d "registration_form[password]=password123" \
  -d "_csrf_token=csrf-token" \

echo -e "\n-----------------------------------\n"


echo "🟢 Test: GET /admin (niezalogowany)"
curl -i -X GET http://localhost:8000/admin
echo -e "\n-----------------------------------\n"


echo "🟢 Test: POST /login (logowanie)"
curl -X POST http://localhost:8000/login \
  -d "email=axx@example.com&password=password&_token=csrf-token"
echo -e "\n-----------------------------------\n"