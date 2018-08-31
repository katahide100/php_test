const assert   = require('power-assert');
const commonModule = require('../js/common.js');

// テストコード
describe('commonModule' , () => {
    describe('APIからの返却値チェックテスト（validBody）', () => {
        it('【正常テスト】空でない', () => {
            var body = 'test';
            assert.equal(commonModule.validBody(body), true);
        }),
        it('【異常テスト】undefined', () => {
            var body = undefined;
            assert.equal(commonModule.validBody(body), false);
        }),
        it('【異常テスト】null', () => {
            var body = null;
            assert.equal(commonModule.validBody(body), false);
        }),
        it('【異常テスト】空', () => {
            var body = "";
            assert.equal(commonModule.validBody(body), false);
        })
    }),
    describe('JSON変換テスト（parseJSON）', () => {
        it('【正常テスト】JSON形式', () => {
            var str = '{"test1": 1, "test2": 2, "test3": 3}';
            var arr = { test1: 1, test2: 2, test3: 3 };
            assert.deepEqual(commonModule.parseJSON(str), arr);
        }),
        it('【異常テスト】JSON形式でない', () => {
            var str = '"test1": 1, "test2": 2, "test3": 3}';
            assert.equal(commonModule.parseJSON(str), false);
        }),
        it('【異常テスト】返却値にエラーが含まれる', () => {
            var str = '{"error": {code:400,message: "API key not valid. Please pass a valid API key."}}}';
            assert.equal(commonModule.parseJSON(str), false);
        })
    })
    describe('UTF-8変換テスト（toUTF8）', () => {
        it('【正常テスト】変換成功', () => {
            var str = new Buffer([
                0x82,0xA0,0x82,0xA2,0x82,0xA4,0x82,0xA6,0x82,0xA8,
                0x82,0xA9,0x82,0xAB,0x82,0xAD,0x82,0xAF,0x82,0xB1,
                0x82,0xB3,0x82,0xB5,0x82,0xB7,0x82,0xB9,0x82,0xBB,
                0x82,0xBD,0x82,0xBF,0x82,0xC2,0x82,0xC4,0x82,0xC6
            ]);
            assert.equal(commonModule.toUTF8(str), 'あいうえおかきくけこさしすせそたちつてと');
        })
    })
})