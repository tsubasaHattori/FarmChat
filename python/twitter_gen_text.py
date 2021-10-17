# -*- coding: utf-8 -*-

import requests
import sys
import re
import MeCab
import random
from requests_oauthlib import OAuth1
from collections import deque


n_size_char = 3
n_size_word = 2

def main(screen_name, CK, CKS, AT, ATS):
    # 検索パラメタ
    count = 100 # 一回あたりの検索数(最大100/デフォルトは15)
    times = 10 # 検索回数の上限値(最大180/15分でリセット)

    # ツイート検索
    get = Get_tweets()
    tweets = get.search_tweets(CK, CKS, AT, ATS, screen_name, count, times)
    if tweets == 1:
        return print('該当するユーザが見つかりませんでした。\nユーザ名が間違っている可能性があります。')

    # 前処理
    tweets = get.preprocess(tweets)

    # 文字単位
    model = mk_model_char(tweets)
    sentence = mk_sentence_char(model)

    # 単語単位
    # model = mk_model_word(tweets)
    # sentence = mk_sentence_word(model)

    return print(sentence)


def mk_model_char(tweets):
    model = {}
    for text in tweets:
        queue = deque([], n_size_char)
        queue.append("[BOS]")
        for i in range(0, len(text)):
            key = tuple(queue)
            if key not in model:
                model[key] = []
            model[key].append(text[i])
            queue.append(text[i])
        key = tuple(queue)
        if key not in model:
            model[key] = []
        model[key].append("[EOS]")

    return model


def mk_model_word(path):
    model = {}
    tagger = MeCab.Tagger("-Owakati")
    for text in tweets:
        text = tagger.parse(text)

        queue = deque([], n_size_word)
        queue.append("[BOS]")
        wordlist = text.split()
        for word in wordlist:
            key = tuple(queue)
            if key not in model:
                model[key] = []
            model[key].append(word)
            queue.append(word)
        key = tuple(queue)
        if key not in model:
            model[key] = []
        model[key].append("[EOS]")

    return model


def mk_sentence_char(model):
    value_list = []
    queue = deque([], n_size_char)
    queue.append("[BOS]")
    key = tuple(queue)
    while(True):
        key = tuple(queue)
        value = random.choice(model[key])
        if value == "[EOS]":
            break
        value_list.append(value)
        queue.append(value)
    value_list.pop(-1)
    sentence = ''.join(value_list)

    return sentence


def mk_sentence_word(model):
    value_list = []
    queue = deque([], n_size_word)
    queue.append("[BOS]")
    key = tuple(queue)
    while(True):
        key = tuple(queue)
        value = random.choice(model[key])
        if value == "[EOS]":
            break
        value_list.append(value)
        queue.append(value)
    sentence = ''.join(value_list)

    return sentence


class Get_tweets:
    def search_tweets(self, CK, CKS, AT, ATS, screen_name, count, range):
        url = "https://api.twitter.com/1.1/statuses/user_timeline.json"
        params = {"screen_name": screen_name, "count": count, 'exclude_replies': False, 'include_rts': False}
        auth = OAuth1(CK, CKS, AT, ATS)
        response = requests.get(url, auth=auth, params=params)
        data = response.json()
        if 'errors' in data:
            print('screen_name error')
            return 1
        # ２回目以降のリクエスト
        cnt = 1
        tweets = []
        count_tweets = 0
        while True:
            if len(data) == 0:
                break
            if cnt > range:
                break
            cnt += 1
            for tweet in data:
                tweets.append(tweet['text'])
                count_tweets += 1
                maxid = int(tweet["id"]) - 1
            params = {"screen_name": screen_name, "count": count, 'exclude_replies': True, 'include_rts': False, "max_id": maxid}
            url = "https://api.twitter.com/1.1/statuses/user_timeline.json"
            response = requests.get(url, auth=auth, params=params)
            try:
                data = response.json()
            except KeyError: # リクエスト回数が上限に達した場合のデータのエラー処理
                print('上限まで検索しました')
                break
        print('取得したツイート数 :', count_tweets)
        return tweets

    def preprocess(self, tweets):
        for i in range(len(tweets)):
            tweets[i] = re.sub(r'@\w+', '', tweets[i])  # ユーザーID
            tweets[i] = re.sub(r'https?://[\w/:%#\$&\?\(\)~\.=\+\-]+', '', tweets[i])  # URL
            tweets[i] = re.sub(r'\n{2,}', '\n', tweets[i])  # 空白の行]
            tweets[i] = re.sub(r'#\w+', '', tweets[i])  # ハッシュタグ

        return tweets

if __name__ == '__main__':
    main(sys.argv[1], sys.argv[2], sys.argv[3], sys.argv[4], sys.argv[5])
