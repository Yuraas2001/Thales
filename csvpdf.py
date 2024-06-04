import mysql.connector

# Connexion à la base de données
conn = mysql.connector.connect(
    host="8889",
    user="root",
    password="",
    database="BP"
)

cursor = conn.cursor()
print("Connexion réussie")
cursor.close()
conn.close()


import csv
import mysql.connector

# Fonction pour se connecter à la base de données
def connect_to_database():
    return mysql.connector.connect(
        host="8889",
        user="root",
        password="",
        database="BP"
    )

# Fonction pour récupérer les bonnes pratiques depuis la base de données
def get_bonnes_pratiques(connection):
    cursor = connection.cursor(dictionary=True)
    cursor.execute("SELECT * FROM BonnesPratiques")
    return cursor.fetchall()

# Fonction pour exporter les bonnes pratiques en CSV
def export_bonnes_pratiques_to_csv(bonnes_pratiques):
    with open('bonnes_pratiques.csv', 'w', newline='') as csvfile:
        fieldnames = ['IDBonnePratique', 'Description', 'Etat']
        writer = csv.DictWriter(csvfile, fieldnames=fieldnames)
        writer.writeheader()
        for bp in bonnes_pratiques:
            writer.writerow(bp)

# Connexion à la base de données
connection = connect_to_database()

# Récupération des bonnes pratiques
bonnes_pratiques = get_bonnes_pratiques(connection)

# Exportation en CSV
export_bonnes_pratiques_to_csv(bonnes_pratiques)

# Fermeture de la connexion
connection.close()

print("Les bonnes pratiques ont été exportées en CSV avec succès.")

# Fonction pour exporter les bonnes pratiques en PDF
def export_bonnes_pratiques_to_pdf(bonnes_pratiques):
    doc = SimpleDocTemplate("bonnes_pratiques.pdf", pagesize=letter)
    table_data = [["IDBonnePratique", "Description", "Etat"]]
    for bp in bonnes_pratiques:
        table_data.append([bp['IDBonnePratique'], bp['Description'], bp['Etat']])
    
    table = Table(table_data)
    style = TableStyle([('BACKGROUND', (0, 0), (-1, 0), colors.grey),
                        ('TEXTCOLOR', (0, 0), (-1, 0), colors.whitesmoke),
                        ('ALIGN', (0, 0), (-1, -1), 'CENTER'),
                        ('FONTNAME', (0, 0), (-1, 0), 'Helvetica-Bold'),
                        ('BOTTOMPADDING', (0, 0), (-1, 0), 12),
                        ('BACKGROUND', (0, 1), (-1, -1), colors.beige),
                        ('GRID', (0, 0), (-1, -1), 1, colors.black)])
    
    table.setStyle(style)
    doc.build([table])

# Connexion à la base de données
connection = connect_to_database()

# Récupération des bonnes pratiques
bonnes_pratiques = get_bonnes_pratiques(connection)

# Exportation en PDF
export_bonnes_pratiques_to_pdf(bonnes_pratiques)

# Fermeture de la connexion
connection.close()

print("Les bonnes pratiques ont été exportées en PDF avec succès.")
