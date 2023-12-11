#!/usr/bin/env bash
export MC_MODE=True
export PATH=$PATH:/home/gtarcea/.local/bin
cd /home/gtarcea/workspace/src/github.com/materials-commons/uhcsdb/uhcsdb
bokeh serve --prefix=/uhcsapp/ --allow-websocket-origin materialscommons.org visualize.py
