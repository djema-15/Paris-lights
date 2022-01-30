# Paris-lights
Ce projet est réalisé dans le cadre de garantir à l’utilisateur un accès plus facile à l’information autour d’une adresse saisie, autrement dit, celui-ci a pour objectif principal de proposer les services à proximité d’une adresse donnée. De ce fait, après la saisie de l’adresse par l’utilisateur, le site met à sa disposition une liste complète des centres d’intérêt se trouvant aux alentours, plus précisément dans un rayon d’un ou deux kilomètre (Restaurants, Velib, Espaces verts...). En outre, ce site touche à plusieurs domaines et vise différentes cibles, il s’adresse en priorité aux touristes (visiteurs de Paris). Néanmoins, celui-ci peut également être utile aux entrepreneurs souhaitant investir sur Paris en leur optimisant l’accès aux informations pertinentes concernant les services se trouvant à proximité d’une adresse donnée.
# Installation
## clonner le projet dans votre repertoire:
- cd folder/to/clone-into/
- git clone https://github.com/djema-15/Paris-lights.git
## lancer les containers :
- docker compose up -d 
## executer le container :
- dokcer exec -ti "docker id" bash
## lancer le server:
symfony server:start
## remplir la base de données avec le fichier plbase.sql
- 127.0.0.1:8080 ( console phpmyadmin)
## lancer le web service:
- 127.0.0.1:5000
## deployer sur kubernetes:

