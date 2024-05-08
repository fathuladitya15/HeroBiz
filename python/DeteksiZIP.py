from roboflow import Roboflow
rf = Roboflow(api_key="7bHTkpCMSaLaSiMcY0rO")
project = rf.workspace().project("pines-tree")
model = project.version(3).model

# visualize and save prediction
model.predict("D:\KAMPUS\Skripsi\pinuss_01.png", confidence=40, overlap=30).save("D:\KAMPUS\Skripsi\exportDetection\pinus1.jpg")

import geopandas as gpd
from shapely.geometry import Polygon
import zipfile


# prediksi model
detections = model.predict("D:\KAMPUS\Skripsi\pinuss_01.png", confidence=40, overlap=30).json()

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
gdf.to_file("Output.shp")

# Buat objek ZipFile
with zipfile.ZipFile('D:\KAMPUS\Skripsi\exportSHP\output.zip', 'w') as zipf:
    # Tambahkan file .shp, .shx, dan .dbf ke file .zip
    zipf.write('output.shp')
    zipf.write('output.shx')
    zipf.write('output.dbf')
    zipf.write('output.cpg')

