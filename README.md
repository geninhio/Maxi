# **API TP-Maxi-Ted-Walter : Maxi**

## **Table de matières**

1. [http://10.10.15.168:8001/produits](#http1010151688001produits)
    1. [Requête pour obtenir tous les produits](#requête-pour-obtenir-tous-les-produits)
        1. [GET](#get)
    2. [Exemple pour cette requête](#exemple-pour-cette-requête)
    3. [Réponses de l'API à la requête d'obtention de tous les produits](#réponses-de-lapi-à-la-requête-dobtention-de-tous-les-produits)
        1. [Réponse de l'API avec un code 200](#réponse-de-lapi-avec-un-code-200)
        2. [Réponse de l'API avec un code 400](#réponse-de-lapi-avec-un-code-400)
    4. [Champs de la réponse pour la liste des produits](#champs-de-la-réponse-pour-la-liste-des-produits)
    5. [Requête pour ajouter un produit](#requête-pour-ajouter-un-produit)
        1. [POST](#post)
    6. [Exemple pour la requête](#exemple-pour-la-requête-1)
    7. [Réponses de l'API à la requête de création d'un produit](#réponses-de-lapi-à-la-requête-de-création-dun-produit)
        1. [Réponse de l'API avec un code 201](#réponse-de-lapi-avec-un-code-201)

2. [http://10.10.15.168:8001/produits/{id}](#http1010151688001produitsid)
    1. [Requête pour obtenir un produit](#requête-pour-obtenir-un-produit)
        1. [GET](#get-1)
    2. [Exemple pour la requête](#exemple-pour-la-requête-2)
    3. [Réponses de l'API à la requête d'obtention d'un produit](#réponses-de-lapi-à-la-requête-dobtention-dun-produit)
        1. [Réponse de l'API avec un code 200](#réponse-de-lapi-avec-un-code-200-1)
        2. [Réponse de l'API avec un code 401](#réponse-de-lapi-avec-un-code-401)
    4. [Champs de la réponse pour un produit](#champs-de-la-réponse-pour-un-produit)
    5. [Requête pour désactiver ou réactiver un produit](#requête-pour-désactiver-ou-réactiver-un-produit)
        1. [DELETE](#delete)
    6. [Exemple pour la requête](#exemple-pour-la-requête-3)
    7. [Réponses de l'API à la requête de désactivation ou de réactivation d'un produit](#réponses-de-lapi-à-la-requête-de-désactivation-ou-de-réactivation-dun-produit)
        1. [Réponse de l'API avec un code 200](#réponse-de-lapi-avec-un-code-200-2)
        2. [Réponse de l'API avec un code 403](#réponse-de-lapi-avec-un-code-403)
    8. [Champs de la réponse pour la désactivation ou la réactivation d'un produit](#champs-de-la-réponse-pour-la-désactivation-ou-la-réactivation-dun-produit)

3. [http://10.10.15.168:8001/employes](#http1010151688001employes)
    1. [Requête pour obtenir tous les employés](#requête-pour-obtenir-tous-les-employés)
        1. [GET](#get-2)
    2. [Exemple pour cette requête](#exemple-pour-cette-requête-1)
    3. [Réponses de l'API à la requête d'obtention de tous les employés](#réponses-de-lapi-à-la-requête-dobtention-de-tous-les-employés)
        1. [Réponse de l'API avec un code 200](#réponse-de-lapi-avec-un-code-200-3)
        2. [Réponse de l'API avec un code 400](#réponse-de-lapi-avec-un-code-400-1)
    4. [Champs de la réponse pour la liste des employés](#champs-de-la-réponse-pour-la-liste-des-employés)
    5. [Requête pour ajouter un employé](#requête-pour-ajouter-un-employé)
        1. [POST](#post-1)
    6. [Exemple pour la requête](#exemple-pour-la-requête-4)
    7. [Réponses de l'API à la requête de création d'un employé](#réponses-de-lapi-à-la-requête-de-création-dun-employé)
        1. [Réponse de l'API avec un code 201](#réponse-de-lapi-avec-un-code-201-1)

4. [http://10.10.15.168:8001/employes/{id}](#http1010151688001employesid)
    1. [Requête pour obtenir un employé](#requête-pour-obtenir-un-employé)
        1. [GET](#get-3)
    2. [Exemple pour la requête](#exemple-pour-la-requête-5)
    3. [Réponses de l'API à la requête d'obtention d'un employé](#réponses-de-lapi-à-la-requête-dobtention-dun-employé)
        1. [Réponse de l'API avec un code 200](#réponse-de-lapi-avec-un-code-200-4)
        2. [Réponse de l'API avec un code 403](#réponse-de-lapi-avec-un-code-403-1)
    4. [Champs de la réponse pour un employé](#champs-de-la-réponse-pour-un-employé)
    5. [Requête pour désactiver ou réactiver un employé](#requête-pour-désactiver-ou-réactiver-un-employé)
        1. [DELETE](#delete-1)
    6. [Exemple pour la requête](#exemple-pour-la-requête-6)
    7. [Réponses de l'API à la requête de désactivation ou de réactivation d'un employé](#réponses-de-lapi-à-la-requête-de-désactivation-ou-de-réactivation-dun-employé)
        1. [Réponse de l'API avec un code 200](#réponse-de-lapi-avec-un-code-200-5)
        2. [Réponse de l'API avec un code 403](#réponse-de-lapi-avec-un-code-403-2)
    8. [Champs de la réponse pour la désactivation ou la réactivation d'un employé](#champs-de-la-réponse-pour-la-désactivation-ou-la-réactivation-dun-employé)

5. [http://10.10.15.168:8001/paniers](#http1010151688001paniers)
    1. [Requête pour obtenir tous les paniers](#requête-pour-obtenir-tous-les-paniers)
        1. [GET](#get-4)
    2. [Exemple pour cette requête](#exemple-pour-cette-requête-2)
    3. [Réponses de l'API à la requête d'obtention de tous les paniers](#réponses-de-lapi-à-la-requête-dobtention-de-tous-les-paniers)
        1. [Réponse de l'API avec un code 200](#réponse-de-lapi-avec-un-code-200-6)
        2. [Réponse de l'API avec un code 400](#réponse-de-lapi-avec-un-code-400-2)
    4. [Champs de la réponse pour la liste des paniers](#champs-de-la-réponse-pour-la-liste-des-paniers)
    5. [Requête pour ajouter un panier](#requête-pour-ajouter-un-panier)
        1. [POST](#post-2)
    6. [Exemple pour la requête](#exemple-pour-la-requête-7)
    7. [Réponses de l'API à la requête de création d'un panier](#réponses-de-lapi-à-la-requête-de-création-dun-panier)
        1. [Réponse de l'API avec un code 201](#réponse-de-lapi-avec-un-code-201-2)

6. [http://10.10.15.168:8001/paniers/{id}](#http1010151688001paniersid)
    1. [Requête pour obtenir un panier](#requête-pour-obtenir-un-panier)
        1. [GET](#get-5)
    2. [Exemple pour la requête](#exemple-pour-la-requête-8)
    3. [Réponses de l'API à la requête d'obtention d'un panier](#réponses-de-lapi-à-la-requête-dobtention-dun-panier)
        1. [Réponse de l'API avec un code 200](#réponse-de-lapi-avec-un-code-200-7)
        2. [Réponse de l'API avec un code 404](#réponse-de-lapi-avec-un-code-404)
    4. [Champs de la réponse pour un panier](#champs-de-la-réponse-pour-un-panier)
    5. [Requête pour désactiver ou réactiver un panier](#requête-pour-désactiver-ou-réactiver-un-panier)
        1. [DELETE](#delete-2)
    6. [Exemple pour la requête](#exemple-pour-la-requête-9)
    7. [Réponses de l'API à la requête de désactivation ou de réactivation d'un panier](#réponses-de-lapi-à-la-requête-de-désactivation-ou-de-réactivation-dun-panier)
        1. [Réponse de l'API avec un code 200](#réponse-de-lapi-avec-un-code-200-8)
        2. [Réponse de l'API avec un code 403](#réponse-de-lapi-avec-un-code-403-3)
    8. [Champs de la réponse pour la désactivation ou la réactivation d'un panier](#champs-de-la-réponse-pour-la-désactivation-ou-la-réactivation-dun-panier)

## Clés d'authentification valides
> Accès superviseur : bc4117463eeb7d282e765b2630ed8243
> / Accès commis : 9ef03474263ed138fec2b8afa9af81cb

## http://10.10.15.168:8001/activites
Page html qui permet de voir l'historique des activités du magasin Maxi.
### **Requête pour consulter l'historique du magasin** 
#### **GET**
>  curl http://10.10.15.168:8001/activites

## http://10.10.15.168:8001/produits
Permet d'obtenir l'inventaire de tous les produits disponibles dans le magasin Maxi, et permet aussi d'ajouter un produit dans le magasin.

### **Requête pour obtenir tous les produits** 
#### **GET**
>  curl -H "Authorization: {clé d'authentification}" http://10.10.15.168:8001/produits

| **Paramètres**| **Requisition** | **Description** |
| ----------------- | :----------: | -----------------------------------------------------------------------------------------------|
| `Authorization` | **requis** | Ceci représente la clé à passer en paramètre dans le Header de la Requête pour s'authentifier.|


### **Exemple pour cette requête**
>  curl -H "Authorization:&nbsp;&nbsp; 9eu8888998a415c0763db87da3b347ec" http://10.10.15.168:8001/produits

### **Réponses de l'API à la requète d'obtention de tous les produits**
#
#### **Réponse de l'API avec un code 200 (récupération de la liste des produits disponibles éffectuée avec succès)**
```json
{
    "message": {
        "code": 200,
        "statut": "OK",
        "message": "L'inventaire du magasin a été récupéré avec succès"
    },
    "produits": [
        {
            "id": 1,
            "nom": "steak",
            "prix": 23.65,
            "quantite": 59,
            "marque": {
                "id": 2,
                "nom": "BoucherHalal"
            },
            "actif": 1
        },
        {
            "id": 2,
            "nom": "lait",
            "prix": 14.25,
            "quantite": 147,
            "marque": {
                "id": 1,
                "nom": "Nestlé"
            },
            "actif": 1
        }
    ]
}

```
#### **Réponse de l'API avec un code 400 (une erreur au niveau de la requête)**
```json
{
    "message": {
        "code": 400,
        "statut": "Bad Request",
        "message": "La requête n'est pas valide et n'a donc pas pu être traitée"
    }
}
```

### **Champs de la réponse pour la liste des produits**

- `message`
    - `code`&nbsp;&nbsp;&nbsp;  Code http du statut de la réponse
    - `statut`&nbsp;&nbsp;&nbsp;  Description textuelle du code du statut http de la réponse 
    - `message`&nbsp;&nbsp;&nbsp;  Message plus détaillé caractérisant le statut de la réponse http
- `produits`&nbsp;&nbsp;&nbsp;  Liste des produits disponibles en stock
    - `id`&nbsp;&nbsp;&nbsp;  Id du produit
    - `nom`&nbsp;&nbsp;&nbsp;  Nom du produit
    - `prix`&nbsp;&nbsp;&nbsp;  Prix unitaire du produit
    - `quantite`&nbsp;&nbsp;&nbsp;  Quantite du produit disponible en stock
    - `marque`
        - `id`&nbsp;&nbsp;&nbsp;  Id de la marque du produit
        - `nom`&nbsp;&nbsp;&nbsp;  Nom de la marque du produit
    - `actif`&nbsp;&nbsp;&nbsp;  État du produit (actif/inactif)

### **Requête pour ajouter un produit** 
#### **POST**
> curl -H "Authorization: {clé d'authentification}" http://10.10.15.168:80801/produits -d '{"prix" : {prix unitaire du produit}, "quantite" : {quantité du produit}, "idMarque" : {Id de la marque du produit}, "nom" : {nom du produit}}'

| **Paramètres**| **Requisition** | **Description** |
| :----------------- | :----------: | -----------------------------------------------------------------------------------------------|
| `prix` | **requis** | Ceci représente le prix unitaire du produit. À passer en paramètre dans le Body de la requête.|
| `quantite` | **requis** | Ceci représente la quantité disponible du produit. À passer en paramètre dans le Body de la requête.|
| `idMarque` | **requis** | Ceci représente le numéro identifiant la marque du produit. À passer en paramètre dans le Body de la requête.|
| `nom` | **requis** | Ceci représente le nom du produit. À passer en paramètre dans le Body de la requête.|
| `Authorization` | **requis** | Ceci représente la clé à passer en paramètre dans le Header de la requête pour s'authentifier.|


### **Exemple pour la requête**
> curl -H "Authentification:&nbsp;&nbsp; 9eu8888998a415c0763db87da3b347ec" http://10.10.15.168:80801/produits -d '{"prix" : {prix unitaire du produit}, "quantite" : {quantité du produit}, "idMarque" : {Id de la marque du produit}, "nom" : {nom du produit}}'

### **Réponses de l'API à la requète de création d'un produit**
#
#### **Réponse de l'API avec un code 201 (création du produit souhaité éffectué avec succès)**
```json
{
    "message": {
        "code": 201,
        "statut": "Created",
        "message": "Le produit a été créé avec succès"
    },
    "id": 1,
    "nom": "steak",
    "prix": 23.65,
    "quantite": 59,
    "marque": {
        "id": 2,
        "nom": "BoucherHalal"
    },
    "actif": 1
}
```

***

## http://10.10.15.168:8001/produits/{id}
Permet d'obtenir un produit disponible dans le magasin Maxi à partir de son id (identifiant), et permet aussi de désactiver ou de réactiver un produit du magasin à partir de son id (identifiant).

### **Requête pour obtenir un produit** 
#### **GET**
>  curl -H "Authorization: {clé d'authentification}" http://10.10.15.168:8001/produits/{id}

| **Paramètres**| **Requisition** | **Description** |
| :----------------- | :----------: | -----------------------------------------------------------------------------------------------|
| `id` | **requis** | Ceci représente le numéro identifiant le produit à afficher. À passer directement dans l'URL de la requête.|
| `Authorization` | **requis** | Ceci représente la clé à passer en paramètre dans le Header de la requête pour s'authentifier.|


### **Exemple pour la requête**
>  curl -H "Authorization:&nbsp;&nbsp; 9eu8888998a415c0763db87da3b347ec" http://10.10.15.168:8001/produits/1

### **Réponses de l'API à la requète d'obtention d'un produit**
#
#### **Réponse de l'API avec un code 200 (récupération du produit souhaité éffectué avec succès)**
```json
{
    "message": {
        "code": 200,
        "statut": "OK",
        "message": "Le produit spécifé a été récupéré avec succès"
    },
    "id": 1,
    "nom": "steak",
    "prix": 23.65,
    "quantite": 59,
    "marque": {
        "id": 2,
        "nom": "BoucherHalal"
    },
    "actif": 1
}
```
#### **Réponse de l'API avec un code 401 (échec de l'authentification)**
```json
{
    "message": {
        "code": 401,
        "statut": "Unauthorized",
        "message": "L'authentification a échoué vous n'êtes pas authorisé à accéder à nos services"
    }
}
```

### **Champs de la réponse pour un produit**

- `message`
    - `code`&nbsp;&nbsp;&nbsp;  Code http du statut de la réponse
    - `statut`&nbsp;&nbsp;&nbsp;  Description textuelle du code du statut http de la réponse 
    - `message`&nbsp;&nbsp;&nbsp;  Message plus détaillé caractérisant le statut de la réponse http
- `id`&nbsp;&nbsp;&nbsp;  Id du produit
- `nom`&nbsp;&nbsp;&nbsp;  Nom du produit
- `prix`&nbsp;&nbsp;&nbsp;  Prix unitaire du produit
- `quantite`&nbsp;&nbsp;&nbsp;  Quantite du produit disponible en stock
- `marque`
    - `id`&nbsp;&nbsp;&nbsp;  Id de la marque du produit
    - `nom`&nbsp;&nbsp;&nbsp;  Nom de la marque du produit
- `actif`&nbsp;&nbsp;&nbsp;  État du produit (actif/inactif)


### **Requête pour désactiver ou réactiver un produit** 
#### **DELETE**
>  curl -H "Authorization: {clé d'authentification}" http://10.10.15.168:8001/produits/{id} -X DELETE 

| **Paramètres**| **Requisition** | **Description** |
| :----------------- | :----------: | -----------------------------------------------------------------------------------------------|
| `id` | **requis** | Ceci représente le numéro identifiant le produit à afficher. À passer directement dans l'URL de la requête.|
| `Authorization` | **requis** | Ceci représente la clé à passer en paramètre dans le Header de la requête pour s'authentifier.|


### **Exemple pour la requête**
>  curl -H "Authorization:&nbsp;&nbsp; 9eu8888998a415c0763db87da3b347ec" http://10.10.15.168:8001/produits/1 -X DELETE 

### **Réponses de l'API à la requète de désactivation ou de réactivation d'un produit**
#
#### **Réponse de l'API avec un code 200 (désactivation ou de réactivation du produit souhaité éffectuée avec succès)**
```json
{
    "message": {
        "code": 200,
        "statut": "OK",
        "message": "Le produit a bel et bien été désactivé."
    },
    "id": 1,
    "nom": "steak",
    "prix": 23.65,
    "quantite": 59,
    "marque": {
        "id": 2,
        "nom": "BoucherHalal"
    },
    "actif": 0
}
```
#### **Réponse de l'API avec un code 403 (permissions insuffisantes pour éffectuer l'action)**
```json
{
    "message": {
        "code": 403,
        "statut": "Forbidden",
        "message": "Vous n'avez pas les permissions requises pour éffectuer cette action"
    }
}
```

### **Champs de la réponse pour la désactivation ou la réactivation d'un produit**

- `message`
    - `code`&nbsp;&nbsp;&nbsp;  Code http du statut de la réponse
    - `statut`&nbsp;&nbsp;&nbsp;  Description textuelle du code du statut http de la réponse 
    - `message`&nbsp;&nbsp;&nbsp;  Message plus détaillé caractérisant le statut de la réponse http
- `id`&nbsp;&nbsp;&nbsp;  Id du produit
- `nom`&nbsp;&nbsp;&nbsp;  Nom du produit
- `prix`&nbsp;&nbsp;&nbsp;  Prix unitaire du produit
- `quantite`&nbsp;&nbsp;&nbsp;  Quantite du produit disponible en stock
- `marque`
    - `id`&nbsp;&nbsp;&nbsp;  Id de la marque du produit
    - `nom`&nbsp;&nbsp;&nbsp;  Nom de la marque du produit
- `actif`&nbsp;&nbsp;&nbsp;  État du produit (actif/inactif)

***

#
## http://10.10.15.168:8001/employes
Permet d'obtenir l'inventaire de tous les employés du magasin Maxi, et permet aussi d'ajouter un employé dans le magasin.

### **Requête pour obtenir tous les employes** 
#### **GET**
>  curl -H "Authorization: {clé d'authentification}" http://10.10.15.168:8001/employes

| **Paramètres**| **Requisition** | **Description** |
| ----------------- | :----------: | -----------------------------------------------------------------------------------------------|
| `Authorization` | **requis** | Ceci représente la clé à passer en paramètre dans le Header de la Requête pour s'authentifier.|


### **Exemple pour cette requête**
>  curl -H "Authorization:&nbsp;&nbsp; 9eu8888998a415c0763db87da3b347ec" http://10.10.15.168:8001/employes

### **Réponses de l'API à la requète d'obtention de tous les employes**
#
#### **Réponse de l'API avec un code 200 (récupération de la liste des employes éffectuée avec succès)**
```json
{
    "message": {
        "code": 200,
        "statut": "OK",
        "message": "Les employés ont été récupérés avec succès."
    },
    "employes": [
        {
            "id": 1,
            "prenom": "Francine",
            "nom": "Tf",
            "dateNaissance": "2000-02-12",
            "sexe": {
                "id": 2,
                "nom": "féminin"
            },
            "role": {
                "id": 2,
                "nom": "commis"
            },
            "poste": {
                "id": 1,
                "nom": "Boucher junior",
                "departement": {
                    "id": 1,
                    "nom": "Viandes"
                }
            },
            "actif": 1
        },
        {
            "id": 2,
            "prenom": "Jean-Michel",
            "nom": "Laliberté",
            "dateNaissance": "1990-08-19",
            "sexe": {
                "id": 1,
                "nom": "masculin"
            },
            "role": {
                "id": 1,
                "nom": "superviseur"
            },
            "poste": {
                "id": 2,
                "nom": "Boucher sénior",
                "departement": {
                    "id": 1,
                    "nom": "Viandes"
                }
            },
            "actif": 1
        }
    ]
}

```
#### **Réponse de l'API avec un code 400 (une erreur au niveau de la requête)**
```json
{
    "message": {
        "code": 400,
        "statut": "Bad Request",
        "message": "La requête n'est pas valide et n'a donc pas pu être traitée"
    }
}
```

### **Champs de la réponse pour la liste des employes**

- `message`
    - `code`&nbsp;&nbsp;&nbsp;  Code http du statut de la réponse
    - `statut`&nbsp;&nbsp;&nbsp;  Description textuelle du code du statut http de la réponse 
    - `message`&nbsp;&nbsp;&nbsp;  Message plus détaillé caractérisant le statut de la réponse http
- `employes`&nbsp;&nbsp;&nbsp;  Liste des employes de l'entreprise maxi
    - `id`&nbsp;&nbsp;&nbsp;  Id de l'employé
    - `prenom`&nbsp;&nbsp;&nbsp;  Prenom de l'employé
    - `nom`&nbsp;&nbsp;&nbsp;  Nom de l'employé
    - `dateNaissance`&nbsp;&nbsp;&nbsp;  Date de naissance de l'employé
    - `sexe`
        - `id`&nbsp;&nbsp;&nbsp;  Id du sexe de l'employé
        - `nom`&nbsp;&nbsp;&nbsp;  Nom du sexe de l'employé
    - `role`
        - `id`&nbsp;&nbsp;&nbsp;  Id du role de l'employé
        - `nom`&nbsp;&nbsp;&nbsp;  Nom du role de l'employé
    - `poste`
        - `id`&nbsp;&nbsp;&nbsp;  Id du poste de l'employé
        - `nom`&nbsp;&nbsp;&nbsp;  Nom du poste de l'employé
        - `departement`
            - `id`&nbsp;&nbsp;&nbsp;  Id du département du poste de l'employé
            - `nom`&nbsp;&nbsp;&nbsp;  Nom du département du poste de l'employé
    - `actif`&nbsp;&nbsp;&nbsp;  État de l'employé (actif/inactif)

### **Requête pour ajouter un employe** 
#### **POST**
> curl -H "Authorization: {clé d'authentification}" http://10.10.15.168:8001/employes -d '{"idRole" : {Id du role de l'employé}, "nomEmploye" : {nom de l'employé}, "prenomEmploye" : {prénom de l'employé}, "dateNaissance" : {date de naissance de l'employé}, "idSexe" : {Id du sexe de l'employé}, "idPoste" : {Id du poste de l'employé}}'


| **Paramètres**| **Requisition** | **Description** |
| :----------------- | :----------: | -----------------------------------------------------------------------------------------------|
| `idRole` | **requis** | Ceci représente l'Id du rôle de l'employé'. À passer en paramètre dans le Body de la requête.|
| `nomEmploye` | **requis** | Ceci représente le nom de l'employé. À passer en paramètre dans le Body de la requête.|
| `prenomEmploye` | **requis** | Ceci représente le prénom de l'employé. À passer en paramètre dans le Body de la requête.|
| `dateNaissance` | **requis** | Ceci représente la date de naissance de l'employé. À passer en paramètre dans le Body de la requête.|
| `idSexe` | **requis** | Ceci représente l'Id du sexe de l'employé. À passer en paramètre dans le Body de la requête.|
| `idPoste` | **requis** | Ceci représente l'Id du poste de l'employé. À passer en paramètre dans le Body de la requête.|
| `Authorization` | **requis** | Ceci représente la clé à passer en paramètre dans le Header de la requête pour s'authentifier.|


### **Exemple pour la requête**
> curl -H "Authentification:&nbsp;&nbsp; 9eu8888998a415c0763db87da3b347ec" http://10.10.15.168:8001/employes -d '{"idRole" : {Id du role de l'employé}, "nomEmploye" : {nom de l'employé}, "prenomEmploye" : {prénom de l'employé}, "dateNaissance" : {date de naissance de l'employé}, "idSexe" : {Id du sexe de l'employé}, "idPoste" : {Id du poste de l'employé}}'

### **Réponses de l'API à la requète de création d'un employé**
#
#### **Réponse de l'API avec un code 201 (création de l'employé souhaité éffectué avec succès)**
```json
{
    "message": {
        "code": 201,
        "statut": "Created",
        "message": "L'employé a été créé avec succès"
    },
    "id": 3,
    "prenom": "Bouchard",
    "nom": "Jean",
    "dateNaissance": "1992-06-22",
    "sexe": {
        "id": 1,
        "nom": "masculin"
    },
    "role": {
        "id": 2,
        "nom": "commis"
    },
    "poste": {
        "id": 2,
        "nom": "Boucher sénior",
        "departement": {
            "id": 1,
            "nom": "Viandes"
        }
    },
    "actif": 1
}
```

***

## http://10.10.15.168:8001/employes/{id}
Permet d'obtenir un employe du magasin Maxi à partir de son id (identifiant), et permet aussi de désactiver ou de réactiver un employé du magasin à partir de son id (identifiant).

### **Requête pour obtenir un employé** 
#### **GET**
>  curl -H "Authorization: {clé d'authentification}" http://10.10.15.168:8001/employes/{id}

| **Paramètres**| **Requisition** | **Description** |
| :----------------- | :----------: | -----------------------------------------------------------------------------------------------|
| `id` | **requis** | Ceci représente le numéro identifiant l'employé à afficher. À passer directement dans l'URL de la requête.|
| `Authorization` | **requis** | Ceci représente la clé à passer en paramètre dans le Header de la requête pour s'authentifier.|


### **Exemple pour la requête**
>  curl -H "Authorization:&nbsp;&nbsp; 9eu8888998a415c0763db87da3b347ec" http://10.10.15.168:8001/employes/1

### **Réponses de l'API à la requète d'obtention d'un employé**
#
#### **Réponse de l'API avec un code 200 (récupération de l'employé souhaité éffectué avec succès)**
```json
{
    "message": {
        "code": 200,
        "statut": "OK",
        "message": "L'employé a été récupéré avec succès."
    },
    "id": 1,
    "prenom": "Francine",
    "nom": "Tf",
    "dateNaissance": "2000-02-12",
    "sexe": {
        "id": 2,
        "nom": "féminin"
    },
    "role": {
        "id": 2,
        "nom": "commis"
    },
    "poste": {
        "id": 1,
        "nom": "Boucher junior",
        "departement": {
            "id": 1,
            "nom": "Viandes"
        }
    },
    "actif": 1
}
```
#### **Réponse de l'API avec un code 403 (permissions insuffisante pour effectuer cette action.)**
```json
{
    "message": {
        "code": 403,
        "statut": "Forbidden",
        "message": "Vous avez été viré de cette entreprise, vous n'êtes plus autorisé à éffectuer la moindre action."
    }
}
```

### **Champs de la réponse pour un employé**

- `message`
    - `code`&nbsp;&nbsp;&nbsp;  Code http du statut de la réponse
    - `statut`&nbsp;&nbsp;&nbsp;  Description textuelle du code du statut http de la réponse 
    - `message`&nbsp;&nbsp;&nbsp;  Message plus détaillé caractérisant le statut de la réponse http
- `id`&nbsp;&nbsp;&nbsp;  Id de l'employé
- `prenom`&nbsp;&nbsp;&nbsp;  Prenom de l'employé
- `nom`&nbsp;&nbsp;&nbsp;  Nom de l'employé
- `dateNaissance`&nbsp;&nbsp;&nbsp;  Date de naissance de l'employé
- `sexe`
    - `id`&nbsp;&nbsp;&nbsp;  Id du sexe de l'employé
    - `nom`&nbsp;&nbsp;&nbsp;  Nom du sexe de l'employé
- `role`
    - `id`&nbsp;&nbsp;&nbsp;  Id du role de l'employé
    - `nom`&nbsp;&nbsp;&nbsp;  Nom du role de l'employé
- `poste`
    - `id`&nbsp;&nbsp;&nbsp;  Id du poste de l'employé
    - `nom`&nbsp;&nbsp;&nbsp;  Nom du poste de l'employé
    - `departement`
        - `id`&nbsp;&nbsp;&nbsp;  Id du département du poste de l'employé
        - `nom`&nbsp;&nbsp;&nbsp;  Nom du département du poste de l'employé
- `actif`&nbsp;&nbsp;&nbsp;  État de l'employé (actif/inactif)


### **Requête pour désactiver ou réactiver un employé** 
#### **DELETE**
>  curl -H "Authorization: {clé d'authentification}" http://10.10.15.168:8001/produits/{id} -X DELETE 

| **Paramètres**| **Requisition** | **Description** |
| :----------------- | :----------: | -----------------------------------------------------------------------------------------------|
| `id` | **requis** | Ceci représente le numéro identifiant l'employé' à désactiver ou réactiver. À passer directement dans l'URL de la requête.|
| `Authorization` | **requis** | Ceci représente la clé à passer en paramètre dans le Header de la requête pour s'authentifier.|


### **Exemple pour la requête**
>  curl -H "Authorization:&nbsp;&nbsp; 9eu8888998a415c0763db87da3b347ec" http://10.10.15.168:8001/employes/1 -X DELETE 

### **Réponses de l'API à la requète de désactivation ou de réactivation d'un employé**
#
#### **Réponse de l'API avec un code 200 (désactivation ou de réactivation du employé souhaité éffectuée avec succès)**
```json
{
    "message": {
        "code": 200,
        "statut": "OK",
        "message": "L'employé a bel et bien été désactivé."
    },
    "id": 1,
    "prenom": "Francine",
    "nom": "Tf",
    "dateNaissance": "2000-02-12",
    "sexe": {
        "id": 2,
        "nom": "féminin"
    },
    "role": {
        "id": 2,
        "nom": "commis"
    },
    "poste": {
        "id": 1,
        "nom": "Boucher junior",
        "departement": {
            "id": 1,
            "nom": "Viandes"
        }
    },
    "actif": 0
}
```
#### **Réponse de l'API avec un code 403 (permissions insuffisantes pour éffectuer l'action)**
```json
{
    "message": {
        "code": 403,
        "statut": "Forbidden",
        "message": "Vous n'avez pas les permissions requises pour éffectuer cette action"
    }
}
```

### **Champs de la réponse pour la désactivation ou la réactivation d'un employé**

- `message`
    - `code`&nbsp;&nbsp;&nbsp;  Code http du statut de la réponse
    - `statut`&nbsp;&nbsp;&nbsp;  Description textuelle du code du statut http de la réponse 
    - `message`&nbsp;&nbsp;&nbsp;  Message plus détaillé caractérisant le statut de la réponse http
- `id`&nbsp;&nbsp;&nbsp;  Id de l'employé
- `prenom`&nbsp;&nbsp;&nbsp;  Prenom de l'employé
- `nom`&nbsp;&nbsp;&nbsp;  Nom de l'employé
- `dateNaissance`&nbsp;&nbsp;&nbsp;  Date de naissance de l'employé
- `sexe`
    - `id`&nbsp;&nbsp;&nbsp;  Id du sexe de l'employé
    - `nom`&nbsp;&nbsp;&nbsp;  Nom du sexe de l'employé
- `role`
    - `id`&nbsp;&nbsp;&nbsp;  Id du role de l'employé
    - `nom`&nbsp;&nbsp;&nbsp;  Nom du role de l'employé
- `poste`
    - `id`&nbsp;&nbsp;&nbsp;  Id du poste de l'employé
    - `nom`&nbsp;&nbsp;&nbsp;  Nom du poste de l'employé
    - `departement`
        - `id`&nbsp;&nbsp;&nbsp;  Id du département du poste de l'employé
        - `nom`&nbsp;&nbsp;&nbsp;  Nom du département du poste de l'employé
- `actif`&nbsp;&nbsp;&nbsp;  État de l'employé (actif/inactif)

***


#
## http://10.10.15.168:8001/paniers
Permet d'obtenir l'inventaire de tous les paniers du magasin Maxi, et permet aussi d'ajouter un panier dans le magasin.

### **Requête pour obtenir tous les paniers** 
#### **GET**
>  curl -H "Authorization: {clé d'authentification}" http://10.10.15.168:8001/paniers

| **Paramètres**| **Requisition** | **Description** |
| ----------------- | :----------: | -----------------------------------------------------------------------------------------------|
| `Authorization` | **requis** | Ceci représente la clé à passer en paramètre dans le Header de la Requête pour s'authentifier.|


### **Exemple pour cette requête**
>  curl -H "Authorization:&nbsp;&nbsp; 9eu8888998a415c0763db87da3b347ec" http://10.10.15.168:8001/paniers

### **Réponses de l'API à la requète d'obtention de tous les paniers**
#
#### **Réponse de l'API avec un code 200 (récupération de la liste des paniers éffectuée avec succès)**
```json
{
    "message": {
        "code": 200,
        "statut": "OK",
        "message": "ok"
    },
    "paniers": [
        {
            "id": 1,
            "dateCreation": "2025-10-27",
            "produits": [
                {
                    "produit": {
                        "id": 1,
                        "nom": "steak",
                        "prix": 23.65,
                        "quantite": 59,
                        "marque": {
                            "id": 2,
                            "nom": "BoucherHalal"
                        },
                        "actif": 1
                    },
                    "quantite": 5
                },
                {
                    "produit": {
                        "id": 2,
                        "nom": "lait",
                        "prix": 14.25,
                        "quantite": 147,
                        "marque": {
                            "id": 1,
                            "nom": "Nestlé"
                        },
                        "actif": 1
                    },
                    "quantite": 6
                }
            ],
            "employe": {
                "id": 1,
                "prenom": "Francine",
                "nom": "Tf",
                "dateNaissance": "2000-02-12",
                "sexe": {
                    "id": 2,
                    "nom": "féminin"
                },
                "role": {
                    "id": 2,
                    "nom": "commis"
                },
                "poste": {
                    "id": 1,
                    "nom": "Boucher junior",
                    "departement": {
                        "id": 1,
                        "nom": "Viandes"
                    }
                },
                "actif": 1
            },
            "actif": 1
        },
        {
            "id": 2,
            "dateCreation": "2025-10-27",
            "produits": [
                {
                    "produit": {
                        "id": 1,
                        "nom": "steak",
                        "prix": 23.65,
                        "quantite": 59,
                        "marque": {
                            "id": 2,
                            "nom": "BoucherHalal"
                        },
                        "actif": 1
                    },
                    "quantite": 4
                }
            ],
            "employe": {
                "id": 2,
                "prenom": "Jean-Michel",
                "nom": "Laliberté",
                "dateNaissance": "1990-08-19",
                "sexe": {
                    "id": 1,
                    "nom": "masculin"
                },
                "role": {
                    "id": 1,
                    "nom": "superviseur"
                },
                "poste": {
                    "id": 2,
                    "nom": "Boucher sénior",
                    "departement": {
                        "id": 1,
                        "nom": "Viandes"
                    }
                },
                "actif": 1
            },
            "actif": 1
        },
        {
            "id": 3,
            "dateCreation": "2025-11-11",
            "produits": [
                {
                    "produit": {
                        "id": 1,
                        "nom": "steak",
                        "prix": 23.65,
                        "quantite": 59,
                        "marque": {
                            "id": 2,
                            "nom": "BoucherHalal"
                        },
                        "actif": 1
                    },
                    "quantite": 3
                },
                {
                    "produit": {
                        "id": 2,
                        "nom": "lait",
                        "prix": 14.25,
                        "quantite": 147,
                        "marque": {
                            "id": 1,
                            "nom": "Nestlé"
                        },
                        "actif": 1
                    },
                    "quantite": 3
                }
            ],
            "employe": {
                "id": 2,
                "prenom": "Jean-Michel",
                "nom": "Laliberté",
                "dateNaissance": "1990-08-19",
                "sexe": {
                    "id": 1,
                    "nom": "masculin"
                },
                "role": {
                    "id": 1,
                    "nom": "superviseur"
                },
                "poste": {
                    "id": 2,
                    "nom": "Boucher sénior",
                    "departement": {
                        "id": 1,
                        "nom": "Viandes"
                    }
                },
                "actif": 1
            },
            "actif": 1
        }
    ]
}

```
#### **Réponse de l'API avec un code 400 (une erreur au niveau de la requête)**
```json
{
    "message": {
        "code": 400,
        "statut": "Bad Request",
        "message": "La requête n'est pas valide et n'a donc pas pu être traitée"
    }
}
```

### **Champs de la réponse pour la liste des paniers**

- `message`
    - `code`&nbsp;&nbsp;&nbsp;  Code http du statut de la réponse
    - `statut`&nbsp;&nbsp;&nbsp;  Description textuelle du code du statut http de la réponse 
    - `message`&nbsp;&nbsp;&nbsp;  Message plus détaillé caractérisant le statut de la réponse http
- `paniers`&nbsp;&nbsp;&nbsp;  Liste des paniers de l'entreprise maxi
    - `id`&nbsp;&nbsp;&nbsp;  Id du panier
    - `dateCreation`&nbsp;&nbsp;&nbsp;  Date de création du panier
    - `produits`&nbsp;&nbsp;&nbsp;  Liste des produits présents dans le panier
        - `produit`
            - `id`&nbsp;&nbsp;&nbsp;  Id du produit
            - `nom`&nbsp;&nbsp;&nbsp;  Nom du produit
            - `prix`&nbsp;&nbsp;&nbsp;  Prix unitaire du produit
            - `quantite`&nbsp;&nbsp;&nbsp;  Quantite restante du produit en stock
            - `marque`
                - `id`&nbsp;&nbsp;&nbsp;  Id de la marque du produit
                - `nom`&nbsp;&nbsp;&nbsp;  Nom de la marque du produit
            - `actif`&nbsp;&nbsp;&nbsp;  État du produit (actif/inactif)
        - `quantite`&nbsp;&nbsp;&nbsp;  Quantite acheté du produit
    - `employe`&nbsp;&nbsp;&nbsp;  Employé ayant constitué le panier
        - `id`&nbsp;&nbsp;&nbsp;  Id de l'employé
        - `prenom`&nbsp;&nbsp;&nbsp;  Prenom de l'employé
        - `nom`&nbsp;&nbsp;&nbsp;  Nom de l'employé
        - `dateNaissance`&nbsp;&nbsp;&nbsp;  Date de naissance de l'employé
        - `sexe`
            - `id`&nbsp;&nbsp;&nbsp;  Id du sexe de l'employé
            - `nom`&nbsp;&nbsp;&nbsp;  Nom du sexe de l'employé
        - `role`
            - `id`&nbsp;&nbsp;&nbsp;  Id du role de l'employé
            - `nom`&nbsp;&nbsp;&nbsp;  Nom du role de l'employé
        - `poste`
            - `id`&nbsp;&nbsp;&nbsp;  Id du poste de l'employé
            - `nom`&nbsp;&nbsp;&nbsp;  Nom du poste de l'employé
            - `departement`
                - `id`&nbsp;&nbsp;&nbsp;  Id du département du poste de l'employé
                - `nom`&nbsp;&nbsp;&nbsp;  Nom du département du poste de l'employé
        - `actif`&nbsp;&nbsp;&nbsp;  État de l'employé (actif/inactif)
    - `actif`&nbsp;&nbsp;&nbsp;  État du panier (actif/inactif)

### **Requête pour ajouter un panier** 
#### **POST**
> curl -H "Authorization: {clé d'authentification}" http://10.10.15.168:8001/paniers -d '{"panier" : [{"idProduit": {Id du produit à acheter}, "quantite" : {quantité à acheter}}]}'


| **Paramètres**| **Requisition** | **Description** |
| :----------------- | :----------: | -----------------------------------------------------------------------------------------------|
| `panier` | **requis** | Ceci représente la liste de produits voulus dans le panier. À passer en paramètre dans le Body de la requête.|
| `idProduit` | **requis** | Ceci représente l'Id du produit voulu dans le panier. À passer en paramètre dans le Body de la requête.|
| `quantite` | **requis** | Ceci représente la quantité du produit voulu dans le panier. À passer en paramètre dans le Body de la requête.|
| `Authorization` | **requis** | Ceci représente la clé à passer en paramètre dans le Header de la requête pour s'authentifier.|


### **Exemple pour la requête**
> curl -H "Authentification:&nbsp;&nbsp; 9eu8888998a415c0763db87da3b347ec" http://10.10.15.168:8001/paniers -d '{"panier" : [{"idProduit": {Id du produit à acheter}, "quantite" : {quantité à acheter}}]}'

### **Réponses de l'API à la requète de création d'un panier**
#
#### **Réponse de l'API avec un code 201 (création du panier souhaité éffectué avec succès)**
```json
{
    "message": {
        "code": 201,
        "statut": "Created",
        "message": "Le panier a été créé avec succès"
    },
    "id": 3,
    "dateCreation": "2025-11-11",
    "produits": [
        {
            "produit": {
                "id": 1,
                "nom": "steak",
                "prix": 23.65,
                "quantite": 59,
                "marque": {
                    "id": 2,
                    "nom": "BoucherHalal"
                },
                "actif": 1
            },
            "quantite": 3
        },
        {
            "produit": {
                "id": 2,
                "nom": "lait",
                "prix": 14.25,
                "quantite": 147,
                "marque": {
                    "id": 1,
                    "nom": "Nestlé"
                },
                "actif": 1
            },
            "quantite": 3
        }
    ],
    "employe": {
        "id": 2,
        "prenom": "Jean-Michel",
        "nom": "Laliberté",
        "dateNaissance": "1990-08-19",
        "sexe": {
            "id": 1,
            "nom": "masculin"
        },
        "role": {
            "id": 1,
            "nom": "superviseur"
        },
        "poste": {
            "id": 2,
            "nom": "Boucher sénior",
            "departement": {
                "id": 1,
                "nom": "Viandes"
            }
        },
        "actif": 1
    },
    "actif": 1
}
```

***

## http://10.10.15.168:8001/paniers/{id}
Permet d'obtenir un panier du magasin Maxi à partir de son id (identifiant), et permet aussi de désactiver ou de réactiver un panier du magasin à partir de son id (identifiant).

### **Requête pour obtenir un panier** 
#### **GET**
>  curl -H "Authorization: {clé d'authentification}" http://10.10.15.168:8001/paniers/{id}

| **Paramètres**| **Requisition** | **Description** |
| :----------------- | :----------: | -----------------------------------------------------------------------------------------------|
| `id` | **requis** | Ceci représente le numéro identifiant le panier à afficher. À passer directement dans l'URL de la requête.|
| `Authorization` | **requis** | Ceci représente la clé à passer en paramètre dans le Header de la requête pour s'authentifier.|


### **Exemple pour la requête**
>  curl -H "Authorization:&nbsp;&nbsp; 9eu8888998a415c0763db87da3b347ec" http://10.10.15.168:8001/paniers/1

### **Réponses de l'API à la requète d'obtention d'un panier**
#
#### **Réponse de l'API avec un code 200 (récupération du panier souhaité éffectué avec succès)**
```json
{
    "message": {
        "code": 200,
        "statut": "OK",
        "message": "Le panier a été récupéré avec succès."
    },
    "id": 1,
    "dateCreation": "2025-10-27",
    "produits": [
        {
            "produit": {
                "id": 1,
                "nom": "steak",
                "prix": 23.65,
                "quantite": 59,
                "marque": {
                    "id": 2,
                    "nom": "BoucherHalal"
                },
                "actif": 1
            },
            "quantite": 5
        },
        {
            "produit": {
                "id": 2,
                "nom": "lait",
                "prix": 14.25,
                "quantite": 147,
                "marque": {
                    "id": 1,
                    "nom": "Nestlé"
                },
                "actif": 1
            },
            "quantite": 6
        }
    ],
    "employe": {
        "id": 1,
        "prenom": "Francine",
        "nom": "Tf",
        "dateNaissance": "2000-02-12",
        "sexe": {
            "id": 2,
            "nom": "féminin"
        },
        "role": {
            "id": 2,
            "nom": "commis"
        },
        "poste": {
            "id": 1,
            "nom": "Boucher junior",
            "departement": {
                "id": 1,
                "nom": "Viandes"
            }
        },
        "actif": 1
    },
    "actif": 1
}
```
#### **Réponse de l'API avec un code 404 (ressource introuvable.)**
```json
{
    "message": {
        "code": 404,
        "statut": "Not Found",
        "message": "La ressource demandée n'a pas été trouvée"
    }
}
```

### **Champs de la réponse pour un panier**

- `message`
    - `code`&nbsp;&nbsp;&nbsp;  Code http du statut de la réponse
    - `statut`&nbsp;&nbsp;&nbsp;  Description textuelle du code du statut http de la réponse 
    - `message`&nbsp;&nbsp;&nbsp;  Message plus détaillé caractérisant le statut de la réponse http
- `id`&nbsp;&nbsp;&nbsp;  Id du panier
- `dateCreation`&nbsp;&nbsp;&nbsp;  Date de création du panier
- `produits`&nbsp;&nbsp;&nbsp;  Liste des produits présents dans le panier
    - `produit`
        - `id`&nbsp;&nbsp;&nbsp;  Id du produit
        - `nom`&nbsp;&nbsp;&nbsp;  Nom du produit
        - `prix`&nbsp;&nbsp;&nbsp;  Prix unitaire du produit
        - `quantite`&nbsp;&nbsp;&nbsp;  Quantite restante du produit en stock
        - `marque`
            - `id`&nbsp;&nbsp;&nbsp;  Id de la marque du produit
            - `nom`&nbsp;&nbsp;&nbsp;  Nom de la marque du produit
        - `actif`&nbsp;&nbsp;&nbsp;  État du produit (actif/inactif)
    - `quantite`&nbsp;&nbsp;&nbsp;  Quantite acheté du produit
- `employe`&nbsp;&nbsp;&nbsp;  Employé ayant constitué le panier
    - `id`&nbsp;&nbsp;&nbsp;  Id de l'employé
    - `prenom`&nbsp;&nbsp;&nbsp;  Prenom de l'employé
    - `nom`&nbsp;&nbsp;&nbsp;  Nom de l'employé
    - `dateNaissance`&nbsp;&nbsp;&nbsp;  Date de naissance de l'employé
    - `sexe`
        - `id`&nbsp;&nbsp;&nbsp;  Id du sexe de l'employé
        - `nom`&nbsp;&nbsp;&nbsp;  Nom du sexe de l'employé
    - `role`
        - `id`&nbsp;&nbsp;&nbsp;  Id du role de l'employé
        - `nom`&nbsp;&nbsp;&nbsp;  Nom du role de l'employé
    - `poste`
        - `id`&nbsp;&nbsp;&nbsp;  Id du poste de l'employé
        - `nom`&nbsp;&nbsp;&nbsp;  Nom du poste de l'employé
        - `departement`
            - `id`&nbsp;&nbsp;&nbsp;  Id du département du poste de l'employé
            - `nom`&nbsp;&nbsp;&nbsp;  Nom du département du poste de l'employé
    - `actif`&nbsp;&nbsp;&nbsp;  État de l'employé (actif/inactif)
- `actif`&nbsp;&nbsp;&nbsp;  État du panier (actif/inactif)


### **Requête pour désactiver ou réactiver un panier** 
#### **DELETE**
>  curl -H "Authorization: {clé d'authentification}" http://10.10.15.168:8001/produits/{id} -X DELETE 

| **Paramètres**| **Requisition** | **Description** |
| :----------------- | :----------: | -----------------------------------------------------------------------------------------------|
| `id` | **requis** | Ceci représente le numéro identifiant le panier à désactiver ou réactiver. À passer directement dans l'URL de la requête.|
| `Authorization` | **requis** | Ceci représente la clé à passer en paramètre dans le Header de la requête pour s'authentifier.|


### **Exemple pour la requête**
>  curl -H "Authorization:&nbsp;&nbsp; 9eu8888998a415c0763db87da3b347ec" http://10.10.15.168:8001/paniers/1 -X DELETE 

### **Réponses de l'API à la requète de désactivation ou de réactivation d'un panier**
#
#### **Réponse de l'API avec un code 200 (désactivation ou de réactivation du panier souhaité éffectuée avec succès)**
```json
{
    "message": {
        "code": 200,
        "statut": "OK",
        "message": "Le panier a bel et bien été désactivé."
    },
    "id": 1,
    "dateCreation": "2025-10-27",
    "produits": [
        {
            "produit": {
                "id": 1,
                "nom": "steak",
                "prix": 23.65,
                "quantite": 59,
                "marque": {
                    "id": 2,
                    "nom": "BoucherHalal"
                },
                "actif": 1
            },
            "quantite": 5
        },
        {
            "produit": {
                "id": 2,
                "nom": "lait",
                "prix": 14.25,
                "quantite": 147,
                "marque": {
                    "id": 1,
                    "nom": "Nestlé"
                },
                "actif": 1
            },
            "quantite": 6
        }
    ],
    "employe": {
        "id": 1,
        "prenom": "Francine",
        "nom": "Tf",
        "dateNaissance": "2000-02-12",
        "sexe": {
            "id": 2,
            "nom": "féminin"
        },
        "role": {
            "id": 2,
            "nom": "commis"
        },
        "poste": {
            "id": 1,
            "nom": "Boucher junior",
            "departement": {
                "id": 1,
                "nom": "Viandes"
            }
        },
        "actif": 1
    },
    "actif": 0
}
```
#### **Réponse de l'API avec un code 403 (permissions insuffisantes pour éffectuer l'action)**
```json
{
    "message": {
        "code": 403,
        "statut": "Forbidden",
        "message": "Vous n'avez pas les permissions requises pour éffectuer cette action"
    }
}
```

### **Champs de la réponse pour la désactivation ou la réactivation d'un panier**

- `message`
    - `code`&nbsp;&nbsp;&nbsp;  Code http du statut de la réponse
    - `statut`&nbsp;&nbsp;&nbsp;  Description textuelle du code du statut http de la réponse 
    - `message`&nbsp;&nbsp;&nbsp;  Message plus détaillé caractérisant le statut de la réponse http
- `id`&nbsp;&nbsp;&nbsp;  Id du panier
- `dateCreation`&nbsp;&nbsp;&nbsp;  Date de création du panier
- `produits`&nbsp;&nbsp;&nbsp;  Liste des produits présents dans le panier
    - `produit`
        - `id`&nbsp;&nbsp;&nbsp;  Id du produit
        - `nom`&nbsp;&nbsp;&nbsp;  Nom du produit
        - `prix`&nbsp;&nbsp;&nbsp;  Prix unitaire du produit
        - `quantite`&nbsp;&nbsp;&nbsp;  Quantite restante du produit en stock
        - `marque`
            - `id`&nbsp;&nbsp;&nbsp;  Id de la marque du produit
            - `nom`&nbsp;&nbsp;&nbsp;  Nom de la marque du produit
        - `actif`&nbsp;&nbsp;&nbsp;  État du produit (actif/inactif)
    - `quantite`&nbsp;&nbsp;&nbsp;  Quantite acheté du produit
- `employe`&nbsp;&nbsp;&nbsp;  Employé ayant constitué le panier
    - `id`&nbsp;&nbsp;&nbsp;  Id de l'employé
    - `prenom`&nbsp;&nbsp;&nbsp;  Prenom de l'employé
    - `nom`&nbsp;&nbsp;&nbsp;  Nom de l'employé
    - `dateNaissance`&nbsp;&nbsp;&nbsp;  Date de naissance de l'employé
    - `sexe`
        - `id`&nbsp;&nbsp;&nbsp;  Id du sexe de l'employé
        - `nom`&nbsp;&nbsp;&nbsp;  Nom du sexe de l'employé
    - `role`
        - `id`&nbsp;&nbsp;&nbsp;  Id du role de l'employé
        - `nom`&nbsp;&nbsp;&nbsp;  Nom du role de l'employé
    - `poste`
        - `id`&nbsp;&nbsp;&nbsp;  Id du poste de l'employé
        - `nom`&nbsp;&nbsp;&nbsp;  Nom du poste de l'employé
        - `departement`
            - `id`&nbsp;&nbsp;&nbsp;  Id du département du poste de l'employé
            - `nom`&nbsp;&nbsp;&nbsp;  Nom du département du poste de l'employé
    - `actif`&nbsp;&nbsp;&nbsp;  État de l'employé (actif/inactif)
- `actif`&nbsp;&nbsp;&nbsp;  État du panier (actif/inactif)