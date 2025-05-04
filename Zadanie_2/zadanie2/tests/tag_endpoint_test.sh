#!/bin/bash

API_URL="http://localhost:8000"

echo "GET: Formularz tworzenia tagu"
curl -i "$API_URL/tag/create"
echo -e "\n-----------------------------------\n"

echo "POST: Tworzenie nowego tagu"
curl -i -X POST "$API_URL/tag/create" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "tag[name]=TestowyTag"
echo -e "\n-----------------------------------\n"


echo "GET: Lista tagów"
curl -i "$API_URL/tags"
echo -e "\n-----------------------------------\n"


echo "GET: Szczegóły tagu (ID=1)"
curl -i "$API_URL/tag/1"
echo -e "\n-----------------------------------\n"

echo "GET: Formularz edycji tagu (ID=1)"
curl -i "$API_URL/tag/1/edit"
echo -e "\n-----------------------------------\n"


echo "POST: Edycja tagu (ID=1)"
curl -i -X POST "$API_URL/tag/1/edit" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "tag[name]=ZmienionyTag"
echo -e "\n-----------------------------------\n"


echo "GET: Potwierdzenie usunięcia tagu (ID=1)"
curl -i "$API_URL/tag/1/delete"
echo -e "\n-----------------------------------\n"


echo "POST: Usunięcie tagu (ID=1)"
curl -i -X POST "$API_URL/tag/1/delete"
echo -e "\n-----------------------------------\n"
