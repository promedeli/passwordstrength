<style>
    #password-strength-meter[value="1"]::-webkit-meter-optimum-value {
        background: {$color1};
    }
    #password-strength-meter[value="2"]::-webkit-meter-optimum-value {
        background: {$color2};
    }

    #password-strength-meter[value="3"]::-webkit-meter-optimum-value {
        background: {$color3};
    }

    #password-strength-meter[value="4"]::-webkit-meter-optimum-value {
        background: {$color4};
    }

    /* Gecko based browsers */
    #password-strength-meter[value="1"]::-moz-meter-bar {
        background: {$color1};
    }

    #password-strength-meter[value="2"]::-moz-meter-bar {
        background: {$color2};
    }

    #password-strength-meter[value="3"]::-moz-meter-bar {
        background: {$color3};
    }

    #password-strength-meter[value="4"]::-moz-meter-bar {
        background: {$color4};
    }
</style>
<script>
    var passwdStrengthConfig = {
        strength: {
            0: "{$s0}", 1: "{$s1}", 2: "{$s2}", 3: "{$s3}", 4: "{$s4}"
        },
        displayText: {$display_text},
        text: "{$text}"
    };
</script>

<div id="meter">
    <meter max="4" id="password-strength-meter"></meter>
    <p id="password-strength-text"></p>
</div>
