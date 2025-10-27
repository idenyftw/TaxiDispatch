# Améliorations possibles pour le projet TaxiDispatch

## 1. Accepter / Refuser commandes coté (chauffeur)

Le chauffeur va pouvoir accepter une commande en cours si il est en train d'utiliser le bon type de voiture si cela est precisé
La commande va ensuite change d'état

## 2. Finaliser une commande (chauffeur)

Si le chauffeur est arrivé à la destination du trajet il pourra ensuite la finaliser. Ensuite le trajet va changer d'état du "ongoing" à "ended"
Ensuite elle sera visible dans la table d'historique du chauffeur et pourra de nouveau accepter une commande en attente

## 3. Ajouter un endpoint pour les commandes des clients et rendre l'application synchrone 

Pour simuler l'utilisation de l'application, un endpoint avec des nouveaux commandes sera ajouté qui va les en generer alèatoirement. Ensuite un systeme de mise à jour sera mis en place pour mettre à jour les tableaux à un interval de temps specifié et afficher les nouveaux informations

