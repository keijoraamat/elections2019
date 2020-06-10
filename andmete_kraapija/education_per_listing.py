import pandas as pd
import matplotlib.pyplot as plot
from matplotlib.pyplot import figure
import numpy as np
import re

plot.interactive(False)
ELECTION_DAY = "2019-03-03"
electionDayTimestamp = pd.Timestamp(ELECTION_DAY)


# search for email domain and return if there is one
def extract_domain(email):
    domain = re.search("@[\w.]+", email)
    if domain:
        return domain.group()


# save counts of education levels per party two dimensional list.
def edus_to_list():
    party_edu = []
    for i in range(len(partyNames)):
        # new temporary dataframe by party names
        party_df = dataFrame.loc[dataFrame['listing'] == partyNames[i]]
        # count education levels and save as dictionary
        party_edu_counts = dict(party_df['education'].value_counts())
        # create dictionary with zeros as keys from educations list
        party_edu_dict = dict((element, 0) for element in educations)
        # Change key in party_edu_dict if it has same in party_edu_counts dictionary
        for key in party_edu_dict:
            for keys in party_edu_counts:
                if keys == key:
                    party_edu_dict[key] = party_edu_counts[keys]
        # extract dictionary party_edu_dict values to a list
        party_edu_counts_list = list(party_edu_dict.values())
        # append list of counts of party education levels to a list
        party_edu.append(party_edu_counts_list)
    return party_edu


dataFrame = pd.read_csv("candidates.csv", index_col='id')

# set column date of birth to datetime type
dataFrame['dateofbirth'] = dataFrame['dateofbirth'].astype('datetime64[D]')

# add column with canidates age
dataFrame['ageonelection'] = (electionDayTimestamp - dataFrame['dateofbirth']).astype('<m8[Y]')

# remove dateofbirth
dataFrame.pop('dateofbirth')

figure(num=None, figsize=(9, 8), dpi=160)
# get list with all listing names
partyNames = dataFrame['listing'].sort_values().unique()
shortPartyNames = ['KESK', 'EKRE', 'REF', 'EVA', 'EÜVP', 'ERE', 'EE200', 'ROH', 'IE', 'SDE', 'ÜKSIK']
print(partyNames)

# get all education levels
educations = dataFrame['education'].unique()
shortEduTitles = ['Kõrg', 'Kesk', 'Põhi', 'Alla põhi']

# get sum of education levels per listing
educationPerListing = dataFrame.groupby('listing')['education'].value_counts()
print(educationPerListing)

data = np.transpose(edus_to_list())
higherEduCounts = data[0]
secondaryEduCounts = data[1]
basicEduCounts = data[2]
belowBasicEduCounts = data[3]


columns = partyNames
rows = shortEduTitles

index = np.arange(len(partyNames)) + 0.55
bar_width = 0.4

# Initialize the vertical-offset for the stacked bar chart.
y_offset = np.zeros(1)
width = 0.35

# Plot bars and create text labels for the table
cell_text = data
# Create bars from higher education counts
plot.bar(index, higherEduCounts, align='center', color='khaki')

# Create bars from secondary education counts
plot.bar(index, secondaryEduCounts, color='darkgoldenrod')

# Create bars from basic education counts
plot.bar(index, basicEduCounts, color='orangered')

# Create bars from below basic education counts
plot.bar(index, belowBasicEduCounts, color='b', width=0.7, snap=True)

# Add a table at the bottom of the axes
the_table = plot.table(cellText=cell_text,
                      rowLabels=rows,
                      colLabels=shortPartyNames,
                       bbox=[0, -0.25, 1, 0.25],
                       colLoc='center',
                      loc='bottom')
plot.subplots_adjust(left=0.11, bottom=0.22)

plot.xticks([])

plot.title('Kanditaatide haridustasemed')

plot.savefig('edu.png')


contactAddressPerListing = dataFrame.groupby('listing')['address'].value_counts()
# print(contactAddressPerListing)

# add column with emails domain address
#print(dataFrame.columns)
#print(dataFrame.head())

# create new column with email domain
dataFrame['domain'] = dataFrame['email'].apply(extract_domain)

print(edus_to_list())

