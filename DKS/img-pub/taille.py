import os
from PIL import Image

# Récupérer le dossier du script
base_path = os.path.dirname(__file__)
image_path = os.path.join(base_path, "pub_mcdo.png")

print("Chemin complet cherché :", image_path)
print("Fichiers dans le dossier :", os.listdir(base_path))

try:
    # Ouvrir l'image
    image = Image.open(image_path)

    # Définir la nouvelle taille
    nouvelle_taille = (255, 300)

    # Redimensionner l'image
    image_redim = image.resize(nouvelle_taille)

    # Convertir en RGB avant de sauvegarder en JPEG
    image_rgb = image_redim.convert("RGB")

    # Sauvegarder en JPEG
    output_path = os.path.join(base_path, "pub_mcdo.png")
    image_rgb.save(output_path)

    print("✅ Image redimensionnée et sauvegardée :", output_path)

except FileNotFoundError:
    print("❌ Erreur : le fichier 'p11.jpg' est introuvable dans", base_path)
