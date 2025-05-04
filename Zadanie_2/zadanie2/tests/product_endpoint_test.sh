#!/bin/bash

API_URL="http://localhost:8000"


echo "GET: Formularz tworzenia produktu"
curl -i "$API_URL/product/create"
echo -e "\n-----------------------------------\n"


echo "🧪 POST: Tworzenie nowego produktu"
curl -i -X POST "$API_URL/product/create" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "product[name]=NowyProdukt" \
  -d "product[price]=99.99" \
  -d "product[category]=NewCat"
echo -e "\n-----------------------------------\n"


echo "🧪 GET: Lista produktów"
curl -i "$API_URL/products"
echo -e "\n-----------------------------------\n"


echo "GET: Szczegóły produktu (ID=2)"
curl -i "$API_URL/product/2"
echo -e "\n-----------------------------------\n"


echo "GET: Formularz edycji produktu (ID=2)"
curl -i "$API_URL/product/2/edit"
echo -e "\n-----------------------------------\n"


echo "POST: Edycja produktu (ID=2)"
curl -i -X POST "$API_URL/product/2/edit" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "product[name]=ZmienionaNazwa" \
  -d "product[price]=123.45" \
  -d "product[category]=zmieniona"
echo -e "\n-----------------------------------\n"


echo "GET: Potwierdzenie usunięcia produktu (ID=2)"
curl -i "$API_URL/product/2/delete"
echo -e "\n-----------------------------------\n"


echo "POST: Usunięcie produktu (ID=2)"
curl -i -X POST "$API_URL/product/2/delete"
echo -e "\n-----------------------------------\n"
