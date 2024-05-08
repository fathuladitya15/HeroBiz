from roboflow import Roboflow

import sys
import geopandas as gpd
from shapely.geometry import Polygon

import zipfile

image_path  = sys.argv[1]
saveFileJPG = sys.argv[2]
filename    = sys.argv[3]
saveFileSHP = sys.argv[4]

shp         = sys.argv[5]
shx         = sys.argv[6]
dbf         = sys.argv[7]
cpg         = sys.argv[8]
saveZip     = sys.argv[9]

# print("File gambar yang diproses:", image_path)
# print("Ini adalah Path penyimpanan:", saveFileJPG)
# print("Ini adalah Nama File : ",filename )


rf = Roboflow(api_key="7bHTkpCMSaLaSiMcY0rO")
project = rf.workspace().project("pines-tree")
model = project.version(3).model
model.predict(image_path, confidence=40, overlap=30).save(saveFileJPG)

# prediksi model
detections = model.predict(image_path, confidence=40, overlap=30).json()

# Misalkan tinggi gambar adalah h
h = 0

# Ubah setiap dictionary dalam list menjadi bounding box dalam format (xmin, ymin, xmax, ymax)
# dan balikkan koordinat y
boxes = [(d['x'], h - d['y'], d['x'] + d['width'], h - (d['y'] + d['height'])) for d in detections['predictions']]

# Ubah setiap bounding box menjadi objek Polygon
polygons = [Polygon([(box[0], box[1]), (box[2], box[1]), (box[2], box[3]), (box[0], box[3])]) for box in boxes]

# Buat GeoDataFrame
gdf = gpd.GeoDataFrame({'geometry': polygons})

# Ekspor ke file .shp
gdf.to_file(shp)

# Buat objek ZipFile
with zipfile.ZipFile(saveZip, 'w') as zipf:
    # Tambahkan file .shp, .shx, dan .dbf ke file .zip
    zipf.write(shp)
    zipf.write(shx)
    zipf.write(dbf)
    zipf.write(cpg)
