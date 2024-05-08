import pickle
from flask import Flask request

with open ("Model/model_numpy.pkl","rb") as model_file:
    model_numpy = pickle.load(model_file)

with open ("Model/model_pandas.pkl","rb") as model_file:
    model_pandas = pickle.load(model_file)

app = Flask(__name__)

@app.route("/")
def hello_world():
    return "<p>Hello, World!</p>"

@app.route('/sapa')
app.run(debug=True)