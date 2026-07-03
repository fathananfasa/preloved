from flask import Flask, request, jsonify

from recom import search_products, reload_index

app = Flask(__name__)


@app.route('/search', methods=['POST'])
def search():

    try:

        data = request.json

        query = data.get('query')

        if not query:

            return jsonify({
                'success': False,
                'message': 'Query kosong'
            }), 400

        results = search_products(query)

        return jsonify(results)

    except Exception as e:

        return jsonify({
            'success': False,
            'message': str(e)
        }), 500


@app.route('/reload', methods=['POST'])
def reload():

    try:

        reload_index()

        return jsonify({
            'success': True,
            'message': 'Index berhasil direload'
        })

    except Exception as e:

        return jsonify({
            'success': False,
            'message': str(e)
        }), 500


if __name__ == '__main__':

    app.run(
        debug=True,
        host='0.0.0.0',
        port=5000
    )
