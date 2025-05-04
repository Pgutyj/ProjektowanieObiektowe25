#!/bin/bash

BASE_URL="http://localhost:8000"
PROFILE_URL="$BASE_URL/profile"


echo "POST: Edytowanie danych usera"
curl -i -X POST "$PROFILE_URL/12/profile_edit" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "user[username]=changed" \
  -d "user[email]=bashtest@example.com"
echo -e "\n-----------------------------------\n"

echo "POST: Edytowanie hasla usera"
curl -i -X POST "$PROFILE_URL/12/password_edit" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "user[password]=p"
echo -e "\n-----------------------------------\n"

echo "POST: Usuwanie danych usera"
curl -i -X POST "$PROFILE_URL/12/delete"
echo -e "\n-----------------------------------\n"