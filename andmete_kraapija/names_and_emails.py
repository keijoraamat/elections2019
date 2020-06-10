import pandas as pd
import matplotlib.pyplot as plot
from matplotlib.pyplot import figure
import numpy as np
import re


# search for email domain and return if there is one
def extract_domain(email):
    domain = re.search("@[\w.]+", email)
    if domain:
        return domain.group()


dataFrame = pd.read_csv("candidates.csv", index_col='id')

# create new column with email domain
dataFrame['domain'] = dataFrame['email'].apply(extract_domain)
