from lxml import html
import requests
import pandas as pd


CANDIDATE_AMOUNT = 1099
CANDIDATE_NO_START = 101
CANDIDATE_NO_STOP = CANDIDATE_AMOUNT+CANDIDATE_NO_START
candidateNo = range(CANDIDATE_NO_START, CANDIDATE_NO_STOP)

XPATH_EDU = '/html/body/section/div/div[3]/div[2]/div[2]/table/tbody/tr[5]/td[2]/p/text()'
XPATH_NAME = '/html/body/section/div/div[3]/div[2]/div[2]/h5/span/text()'
XPATH_EMAIL = '//html/body/section/div/div[3]/div[2]/div[2]/table/tbody/tr[4]/td[2]/a/text()'
XPATH_ADDRESS = '/html/body/section/div/div[3]/div[2]/div[2]/table/tbody/tr[4]/td[2]/p/text()'
XPATH_PARTY = '/html/body/section/div/div[3]/div[2]/div[2]/table/tbody/tr[3]/td[2]/p/text()'
XPATH_LISTING = '/html/body/section/div/div[3]/div[2]/div[2]/table/tbody/tr[1]/td[2]/p/text()'
XPATH_BDAY = '/html/body/section/div/div[3]/div[2]/div[2]/table/tbody/tr[2]/td[2]/p/text()'


def createc(candidate_no):
    base_url = 'https://rk2019.valimised.ee/et/candidates/candidate-'
    url = base_url + str(candidate_no) + '.html'
    page = requests.get(url)
    tree = html.fromstring(page.content)

    print(f"Working on candidate# {candidate_no}")
    raw_name = tree.xpath(XPATH_NAME)
    name = raw_name[0].split()

    raw_email = tree.xpath(XPATH_EMAIL)
    if raw_email:
        for i in range(len(raw_email)):
            if "@" in raw_email[i]:
                email = raw_email[i].lstrip().strip('\xa0 ')
            else:
                email = "Email puudub"
    else:
        email = 'Email puudub'

    raw_address = tree.xpath(XPATH_ADDRESS)
    if raw_address:
        address = raw_address[0].lstrip().strip('\xa0 ')
    else:
        address = 0

    raw_education = tree.xpath(XPATH_EDU)
    if raw_education:
        education = raw_education[0]

    raw_party = tree.xpath(XPATH_PARTY)
    if raw_party:
        party = raw_party[0]

    raw_listing = tree.xpath(XPATH_LISTING)
    if raw_listing:
        listing = raw_listing[0]

    raw_date_of_birth = tree.xpath(XPATH_BDAY)
    if raw_date_of_birth:
        date_of_birth = raw_date_of_birth[0]

    candidate = dict(address=address, dateofbirth=date_of_birth, id=candidate_no, name=name, email=email, education=education, party=party, listing=listing)
    return candidate


candidates = list(map(createc, candidateNo))
dataFrame = pd.DataFrame(candidates)
dataFrame.to_csv('candidates.csv', index=False)
