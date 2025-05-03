from flask import Flask

app = Flask(__name__)

# check discount
@app.route("/api/discountCalculator/<discount>")
def cal(discount):
   if discount >= 3000:
      discount = discount * (1 - 0.03)

   elif discount >= 5000:
      discount = discount * (1 - 0.08)

   elif discount >= 10000:
      discount = discount * (1 - 0.12)

   response_data = {"discount": discount}

   return response_data


if __name__ == "__main__":
   app.run(debug=True,
           host='127.0.0.1',
           port=8080)
