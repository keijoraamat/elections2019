import pandas as pd

dataFrame = pd.read_csv("candidates.csv", index_col='id')
nimi = dataFrame['name']


def eemaldaNimestEbasobivadKomponendid (nimi):
    algusetaNimi = nimi.replace('[\'', '')
    lõputaNimi = algusetaNimi.replace('\']', '')
    keskosataNimi = lõputaNimi.replace('\', \'', ' ')
    return keskosataNimi

nimed = []

for i in dataFrame['name']:
    nimi = eemaldaNimestEbasobivadKomponendid(i)
    print("nimeleidja", eemaldaNimestEbasobivadKomponendid(i))
    nimed.append(nimi)


col=['name']
nimed=pd.DataFrame(nimed, columns=col)
dataFrame['name']=nimed['name'].values

dataFrame.to_csv('kandidaadid_ideega.csv')