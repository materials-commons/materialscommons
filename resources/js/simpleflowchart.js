function parseSimpleFlowchart(lines) {
    let idCounter = 0, nodes = [];
    let seenConditionals = {};
    lines.split("\n").forEach(line => {
        let entries = line.split('->');
        for (let i = 0; i < entries.length; i++) {
            let node, entry = entries[i].trim();
            if (entry.indexOf('?') !== -1) {
                // condition
                let openParen = entry.indexOf('('),
                    closeParen = entry.indexOf(')'),
                    name = entry.substr(0, openParen),
                    direction = entry.substr(openParen + 1, (closeParen - openParen - 1));

                if (!(name in seenConditionals)) {
                    node = {
                        name: name.trim(),
                        _type: 'condition',
                        id: `c_${idCounter}`,
                        last: i === entries.length - 1,
                        direction: direction,
                    };
                    seenConditionals[name] = node;
                } else {
                    let n = seenConditionals[name];
                    node = {
                        name: name.trim(),
                        _type: 'condition',
                        id: n.id,
                        last: i === entries.length - 1,
                        direction: direction,
                    };
                }
            } else {
                // operation
                let openParen = entry.indexOf('('),
                    closeParen = entry.indexOf(')'),
                    direction = "",
                    name = entry;
                if (openParen !== -1 && closeParen !== -1) {
                    name = entry.substr(0, openParen);
                    direction = entry.substr(openParen + 1, (closeParen - openParen - 1));
                }
                node = {
                    name: name.trim(),
                    _type: 'operation',
                    id: `o_${idCounter}`,
                    last: i === entries.length - 1,
                    direction: direction,
                };
            }
            nodes.push(node);
            idCounter++;
        }
    });
    let seenNodes = {};
    let fl = "st=>start: Receive Samples\ne=>end: Finished\n";
    let node1, node2;
    for (let i = 0; i < nodes.length; i++) {
        if (i + 1 === nodes.length) {
            node1 = nodes[i];
            node2 = null;
        } else {
            node1 = nodes[i];
            node2 = nodes[i + 1];
        }

        if (!(node1.id in seenNodes)) {
            seenNodes[node1.id] = true;
            if (node1._type === 'operation') {
                // fl = fl + `${node1.id}=>operation: ${node1.name}:$myfunc\n`;
                fl = fl + `${node1.id}=>operation: ${node1.name}\n`;
            } else {
                fl = fl + `${node1.id}=>condition: ${node1.name}\n`;
            }
        }

        if (node2 !== null) {
            if (!(node2.id in seenNodes)) {
                seenNodes[node2.id] = true;
                if (node2._type === 'operation') {
                    // fl = fl + `${node2.id}=>operation: ${node2.name}:$myfunc\n`;
                    fl = fl + `${node2.id}=>operation: ${node2.name}\n`;
                } else {
                    fl = fl + `${node2.id}=>condition: ${node2.name}\n`;
                }
            }
        }
    }

    fl = connectNodes(nodes, fl);
    return fl;
}

function connectNodes(nodes, fl) {
    let node1, node2;
    fl = fl + `st->${nodes[0].id}\n`;
    for (let i = 0; i < nodes.length; i++) {
        if (i + 1 === nodes.length) {
            node1 = nodes[i];
            node2 = null;
        } else {
            node1 = nodes[i];
            node2 = nodes[i + 1];
        }

        if (node1.last) {
            let nodeStr = generateNodeString(node1);
            fl = fl + `${nodeStr}->e` + (i === nodes.length - 1 ? "" : "\n");
        } else if (node2 !== null) {
            let node1Str = generateNodeString(node1),
                node2Str = generateNodeString(node2);
            if (node2._type === 'condition') {
                node2Str = node2.id;
            }
            fl = fl + `${node1Str}->${node2Str}\n`;
        }
    }

    return fl;
}

function generateNodeString(node) {
    if (node._type === 'operation') {
        return `${node.id}(${node.direction})`;
    }

    // Node is a condition
    return `${node.id}(${node.direction})`;
}

module.exports = {
    parseSimpleFlowchart,
};