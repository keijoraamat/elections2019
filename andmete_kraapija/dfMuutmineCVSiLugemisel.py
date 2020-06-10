import pandas as pd

dataFrame = pd.read_csv("candidates.csv", index_col='id', dtype={'name':list})
nimi = dataFrame['name']

for i in nimi:
    print(i)
    print(type(i))