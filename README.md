Fonction 1 : La détermination des besoins calorifiques dépendamment des objectifs
Input :
Poids (kg)
Taux de graisse (%)

Multiplicateur d’activité :
1.1 = peu ou pas d’exercice dans la semaine
1.2 = 1 à 2 fois/semaine
1.35 = 3 à 5 fois/semaine
1.45 = 6 à 7 fois/semaine
1.6 à 1.8 = 6 à 7 fois/semaine et travail physique
L’objectif de prise de masse (%)
Ou l’objectif de sèche (%)

Traitement :
Maintenance = (370+21.6*(1-tx de graisse)*poids)*mult_activité (kcal)
Objectif prise de masse = (1+obj_prise de masse)*maintenance (kcal)
Objectif sèche = (1+obj_sèche)\*maintenance (kcal)

Output :
Les kcal de maintenance (kcal) avant les cibles de prise de masse ou sèche en %
Cible kcal prise de masse (kcal)
Cible kcal sèche (kcal)

Fonction 2 : La détermination des besoins en macronutriments dépendamment des objectifs
Input :
Le coefficient de Protéines (g/poids de corps ajustable) (g/kg) 1g=4kcal
Le coefficient de lipides (g/poids de corps ajustable) (g/kg) 1g=9kcal
Le coefficient glucides (g/kg) rempli automatiquement variable en fonction de l’objectif 1g=4kcal

Traitement :
La part de protéines = coeff_prot*(1-tx_graisse)*poids (kcal)
La part de lipides = coeff_lipides*(1-tx_graisse)*poids (kcal)
Le coefficient glucides_prise de masse = cible_prise_dm – part_prot – part_lipides (kcal)
Le coefficient glucides_sèche = cible_sèche – part_prot – part_lipides (kcal)

Output :
La répartition en macronutriments en grammes (g)
La répartition en macronutriments en kcal (kcal)
La répartition en macronutriments en pourcentages (%)
Les totaux

Fonction 3 : La saisie de nom de l’aliment et sa quantité nous donne les calories et les macronutriments
Input :
Le nom de l’aliment
La quantité
Output :
Les calories
Les macros nutriments (x3)

Fonction 4 : Mettre en input plusieurs aliments avec une quantité pour chacun et sortir l’addition des calories et macros = Fonction repas
Input :
D
Ouput :
dfd
