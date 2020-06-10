import pandas as pd
import seaborn as sns
import matplotlib.pyplot as plot
from matplotlib.pyplot import figure
import numpy as np


ELECTION_DAY = "2019-03-03"
electionDayTimestamp = pd.Timestamp(ELECTION_DAY)

dataFrame = pd.read_csv("candidates.csv", index_col='id')

# set column date of birth to datetime type
dataFrame['dateofbirth'] = dataFrame['dateofbirth'].astype('datetime64[D]')

# add column with canidates age
dataFrame['ageonelection'] = (electionDayTimestamp - dataFrame['dateofbirth']).astype('<m8[Y]')
dataFrame['ageonelection'] = dataFrame['ageonelection'].astype(int)

# remove dateofbirth
dataFrame.pop('dateofbirth')
print(dataFrame.head())

sns_plot = sns.distplot(dataFrame['ageonelection'], bins=40)

fig = sns_plot.get_figure()
fig.savefig('age_hist.png')

