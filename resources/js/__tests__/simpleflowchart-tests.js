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
        // console.log(fl);
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
        // console.log(fl);
        expect(fl).toEqual(expected);
    });

    test('it parses a simple flowchart with a single conditional at the start', () => {
        let fldef = "ht?(yes)->ht->sem->analysis\nht?(no)->sem->analysis";
        let fl = simplefl.parseSimpleFlowchart(fldef);
        let expected = `st=>start: Receive Samples
e=>end: Finished
c_0=>condition: ht?
o_1=>operation: ht
o_2=>operation: sem
o_3=>operation: analysis
o_5=>operation: sem
o_6=>operation: analysis
st->c_0
c_0(yes)->o_1
o_1->o_2
o_2->o_3
o_3->e
c_0(no)->o_5
o_5->o_6
o_6->e`;
        // console.log(fl);
        expect(fl).toEqual(expected);
    });

    test('it parses a simple flowchart with a single conditional as the second item', () => {
        let fldef = "sanding->ht?(yes)->ht->sem\nht?(no)->sem";
        let fl = simplefl.parseSimpleFlowchart(fldef);
        let expected = `st=>start: Receive Samples
e=>end: Finished
o_0=>operation: sanding
c_1=>condition: ht?
o_2=>operation: ht
o_3=>operation: sem
o_5=>operation: sem
st->o_0
o_0->c_1
c_1(yes)->o_2
o_2->o_3
o_3->e
c_1(no)->o_5
o_5->e`;
        // console.log(fl);
        expect(fl).toEqual(expected);
    });
});