FROM python:3.12

RUN apt-get update -y \
    && apt-get install -y --no-install-recommends build-essential libgl1 libgl1-mesa-dev xvfb

COPY . /opt/app

WORKDIR /opt/app

RUN pip install --root-user-action=ignore -r requirements.txt

ENTRYPOINT ["/opt/app/start_xvfb.sh"]
