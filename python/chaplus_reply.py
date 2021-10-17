# -*- coding: utf-8 -*-

import requests
import json
import sys

def main(name, text, API_KEY):
    name = name + "さん"
    params = {
        "utterance": text,
        "username": name,
        "agentState":{
            "agentName": "私",
            "tone": "normal",
            "age": "200歳"
        },
    }

    url = "https://www.chaplus.jp/v1/chat?apikey=" + API_KEY
    response = requests.post(url, json=params).json()

    # print(json.dumps(response, indent=4, ensure_ascii=False))
    print(response['bestResponse']['utterance'])

    return

if __name__ == '__main__':
    main(sys.argv[1], sys.argv[2], sys.argv[3])
