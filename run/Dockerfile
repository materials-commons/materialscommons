FROM python:3.12

RUN apt-get update -y \
    && apt-get install -y build-essential

COPY . /opt/app

WORKDIR /opt/app

RUN pip install --root-user-action=ignore -r requirements.txt
