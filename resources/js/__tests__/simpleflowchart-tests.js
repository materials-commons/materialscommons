const simplefl = require('../simpleflowchart');

describe('Test parsing simple flowchart language', () => {
    test('it parses a single line flowchart with no conditionals', () => {
        let fldef = `heat treatment 400c/3h->sem`;
        let fl = simplefl.parseSimpleFlowchart(fldef);
        let expected = `st=>start: Receive Samples
e=>end: Finished
o_0=>operation: heat treatment 400c/3h
o_1=>operation: sem
st->o_0
o_0->o_1
o_1->e`;
        console.log(fl);
        expect(fl).toEqual(expected);
    });

    test('it parses a single line flowchart with no conditionals and an odd number of nodes', () => {
        let fldef = "ht->sem->analysis";
        let fl = simplefl.parseSimpleFlowchart(fldef);
        let expected = `st=>start: Receive Samples
e=>end: Finished
o_0=>operation: ht
o_1=>operation: sem
o_2=>operation: analysis
st->o_0
o_0->o_1
o_1->o_2
o_2->e`;
        console.log(fl);
        expect(fl).toEqual(expected);
    });

    test('it parses a simple flowchart with a single conditional', () => {
        let fldef = "ht?(yes)->ht->sem->analysis\nht?(no)->sem->analysis";
        let fl = simplefl.parseSimpleFlowchart(fldef);
        console.log(fl);
        expect(false).toBeTruthy();
    });
});