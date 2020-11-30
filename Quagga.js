// import Quagga.js

class BarcodeReader {
  constructor() {
    // 処理が完了したときに実行される
    Quagga.onProcessed(this._onProcessed.bind(this));
    // バーコードが読み取れたときに実行される
    Quagga.onDetected(this._onDetected.bind(this));
  }

  get config() {
    return {
      // イメージソースの定義
      inputStream: {
        // イメージを表示する場所（デフォルトはid="interactive"）
        target: '#preview',
        // Canvasのサイズ
        size: 640,
        singleChannel: false
      },
      locator: {
        patchSize: "medium",
        halfSample: true
      },
      // バーコードの種類
      decoder: {
        readers: [{
          format: "code_128_reader",
          config: {}
        }]
      },
      // Web Workerの数
      numOfWorker: navigator.hardwareConcurrency || 4,
      // 画像内にバーコードの位置を示す（認識したときに枠で囲む）
      locate: true,
      // ↓ここにイメージソースを設置する
      src: null
    };
  }

  /**
   * バーコードを読み込む
   * @param {DOMString} src イメージソース
   */
  decode(src) {
    const config = Object.assign({}, this.config, { src: src });

    return new Promise((resolve, reject) => {
      Quagga.decodeSingle(config, result => {
        resolve(result);
      });
    })
  }

  /**
   * 処理が完了したときに実行される
   */
  _onProcessed(data) {
    const ctx = Quagga.canvas.ctx.overlay;
    const canvas = Quagga.canvas.dom.overlay;

    if (!data) { return; }

    // 認識したバーコードを囲む
    if (data.boxes) {
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      const hasNotRead = box => box !== data.box;
      data.boxes.filter(hasNotRead).forEach(box => {
        Quagga.ImageDebug.drawPath(box, { x: 0, y: 1 }, ctx, { color: 'green', lineWidth: 2 });
      });
    }

    // 読み取ったバーコードを囲む
    if (data.box) {
      Quagga.ImageDebug.drawPath(data.box, { x: 0, y: 1 }, ctx, { color: 'blue', lineWidth: 2 });
    }

    // 読み取ったバーコードに線を引く
    if (data.codeResult && data.codeResult.code) {
      Quagga.ImageDebug.drawPath(data.line, { x: 'x', y: 'y' }, ctx, { color: 'red', lineWidth: 3 });
    }
  }

  /**
   * バーコード読み取りが成功したときに実行される
   */
  _onDetected(data) {
  }

  getDataURL() {
    return Quagga.canvas.dom.image.toDataURL();
  }
}

function main() {
  const barcodeReader = new BarcodeReader();

  document.getElementById('barcode').addEventListener('change', async (e) => {
    const file = e.target.files[0];
    const src = window.URL.createObjectURL(file);

    const result = await barcodeReader.decode(src);

    document.getElementById('result').value = result.codeResult.code;
  });
}

main();