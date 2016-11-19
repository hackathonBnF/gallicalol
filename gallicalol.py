from flask import Flask
from flask import render_template

def factory():
	app = Flask(__name__)

	return app


app = Flask(__name__)

@app.route("/")
def hello():
    return render_template('create.html')

#app.run()