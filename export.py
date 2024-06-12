import sqlite3
import pandas as pd
from reportlab.lib.pagesizes import letter
from reportlab.platypus import SimpleDocTemplate, Table, TableStyle
from reportlab.lib import colors

def fetch_data_from_db(db_path, table_name):
    # Connect to the SQLite database
    conn = sqlite3.connect(db_path)
    # Fetch the data from the specified table
    query = f"SELECT * FROM {table_name}"
    df = pd.read_sql_query(query, conn)
    # Close the database connection
    conn.close()
    return df

def generate_pdf(dataframe, pdf_path):
    # Create a PDF document
    pdf = SimpleDocTemplate(pdf_path, pagesize=letter)
    # Convert the DataFrame to a list of lists
    data = [dataframe.columns.tolist()] + dataframe.values.tolist()
    # Create a Table object
    table = Table(data)
    # Style the table
    style = TableStyle([
        ('BACKGROUND', (0, 0), (-1, 0), colors.grey),
        ('TEXTCOLOR', (0, 0), (-1, 0), colors.whitesmoke),
        ('ALIGN', (0, 0), (-1, -1), 'CENTER'),
        ('FONTNAME', (0, 0), (-1, 0), 'Helvetica-Bold'),
        ('FONTSIZE', (0, 0), (-1, 0), 12),
        ('BOTTOMPADDING', (0, 0), (-1, 0), 12),
        ('BACKGROUND', (0, 1), (-1, -1), colors.beige),
        ('GRID', (0, 0), (-1, -1), 1, colors.black),
    ])
    table.setStyle(style)
    # Build the PDF
    elements = [table]
    pdf.build(elements)

if __name__ == "__main__":
    # Path to the SQLite database
    db_path = "../Database/base.db"  # Chemin vers votre base de données
    # Name of the table to export
    table_name = "Utilisateurs"  # Nom de la table à exporter
    # Path to the output PDF file
    pdf_path = "utilisateurs.pdf"  # Chemin du fichier PDF de sortie
    
    # Fetch data from the database
    dataframe = fetch_data_from_db(db_path, table_name)
    # Generate the PDF
    generate_pdf(dataframe, pdf_path)
    print(f"PDF generated successfully: {pdf_path}")
