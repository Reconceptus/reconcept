<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 27.08.2018
 * Time: 15:29
 */
$get = Yii::$app->request->get();
$min = !empty($get['minarea']) ? intval($get['minarea']) : 0;
$max = !empty($get['maxarea']) ? intval($get['maxarea']) : 0;
?>
    <script>
        var min = <?=$min?>;
        var max = <?=$max?>;
    </script>
<?php
$js = <<<JS
    (function () {
        var keypressSlider = document.getElementById('keypress'),
            input0 = document.getElementById('input-with-keypress-0'),
            input1 = document.getElementById('input-with-keypress-1'),
            inputs = [input0, input1];

        noUiSlider.create(keypressSlider, {
            start: [min?min:40, max?max:300],
            step: 10,
            connect: true,
            range: {
                'min': 40,
                '25%': [100, 10],
                '65%': [200, 10],
                'max': 300
            },
            format: wNumb({
                decimals: 0
            }),
            pips: {
                mode: 'range',
                density: 3
            }
        });

        keypressSlider.noUiSlider.on('update', function( values, handle ) {
            inputs[handle].value = values[handle];
        });

        function setSliderHandle(i, value) {
            var r = [null,null];
            r[i] = value;
            keypressSlider.noUiSlider.set(r);
        }

        inputs.forEach(function(input, handle) {

            input.addEventListener('change', function(){
                setSliderHandle(handle, this.value);
            });

            input.addEventListener('keydown', function( e ) {

                var values = keypressSlider.noUiSlider.get();
                var value = Number(values[handle]);

                switch ( e.which ) {
                    case 13:
                        setSliderHandle(handle, this.value);
                        break;
                }
            });
        });
    }())
JS;

$this->registerJs($js);