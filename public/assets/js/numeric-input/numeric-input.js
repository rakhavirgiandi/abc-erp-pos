(function ($) {

    // --------------------------
    //   PUBLIC METHODS STORAGE
    // --------------------------
    const methods = {
        getRaw(input) {
            const settings = $(input).data("numericInput-settings");
            const unmask = val => val.replace(/\D+/g, "");

            function parseNumber(str) {
                str = str
                    .replace(settings.prefix, "")
                    .replace(settings.suffix, "")
                    .replace(new RegExp("\\" + settings.separator, "g"), "")
                    .trim();
                return Number(str);
            }

            if (settings?.mask) {
                return Number(unmask($(input).val()));
            }
            return parseNumber($(input).val());
        },

        setValue(input, value) {
            $(input).val(value).trigger("input").trigger("blur");
        },

        clear(input) {
            $(input).val("");
        },

        destroy(input) {
            const $input = $(input);
            $input.off(".numericInput");
            $input.removeAttr("data-numeric-input");
            $input.removeData("numericInput-settings");
        }
    };

    // --------------------------
    //   MAIN PLUGIN FUNCTION
    // --------------------------
    $.fn.numericInput = function (optionsOrMethod, ...args) {

        // ================
        // CALLING A METHOD
        // ================
        if (typeof optionsOrMethod === "string") {
            const methodName = optionsOrMethod;

            if (!methods[methodName]) {
                console.error("numericInput: method tidak ditemukan → " + methodName);
                return this;
            }
        
            if (this.length === 1) {
                return methods[methodName](this[0], ...args);
            }
        
            return this.map((i, input) => methods[methodName](input, ...args));
        }

        // ===================
        // INITIALIZE PLUGIN
        // ===================
        const settings = $.extend({
            decimal: true,
            allowNegative: true,
            minValue: null,
            maxValue: null,
            maxDigits: null,
            separator: null,
            separatorGroup: 3,
            mask: null,
            prefix: "",
            suffix: "",
        }, optionsOrMethod);

        // Save settings
        this.data("numericInput-settings", settings);

        // =======================
        // COPY CODE ORIGINAL ANDA
        // =======================

        function applyMask(rawDigits) {
            let masked = "";
            let digitIndex = 0;

            for (let i = 0; i < settings.mask.length; i++) {
                if (settings.mask[i] === "#") {
                    if (rawDigits[digitIndex]) {
                        masked += rawDigits[digitIndex++];
                    } else {
                        break;
                    }
                } else {
                    masked += settings.mask[i];
                }
            }
            return masked;
        }

        function unmask(val) {
            return val.replace(/\D+/g, "");
        }

        function parseNumber(str) {
            str = str.replace(settings.prefix, "")
                .replace(settings.suffix, "")
                .replace(new RegExp("\\" + settings.separator, "g"), "")
                .trim();
            return Number(str);
        }

        function formatWithSeparator(numStr) {
            if (!settings.separator) return numStr;
            const groupSize = settings.separatorGroup || 3;
            const regex = new RegExp(`\\B(?=(\\d{${groupSize}})+(?!\\d))`, 'g');
            const parts = numStr.split(".");
            parts[0] = parts[0].replace(regex, settings.separator);
            return parts.join(".");
        }

        return this.each(function () {
            const $input = $(this);
            $input.attr("data-numeric-input", true);

            $input.on("input.numericInput", function () {
                let val = $input.val();

                if (settings.mask) {
                    let numericOnly = unmask(val);
                    let masked = applyMask(numericOnly);
                    $input.val(masked);
                    return;
                }

                let cleaned = val;

                if (settings.allowNegative) {
                    cleaned = cleaned.replace(/[^0-9\-.]/g, "");
                } else {
                    cleaned = cleaned.replace(/[^0-9.]/g, "");
                }

                cleaned = cleaned.replace(/(?!^)-/g, "");

                if (!settings.decimal) {
                    cleaned = cleaned.replace(/\./g, "");
                } else {
                    cleaned = cleaned.replace(/(\..*)\./g, "$1");
                }

                let numericRaw = cleaned.replace(/\D/g, "");
                if (settings.maxDigits && numericRaw.length > settings.maxDigits) {
                    numericRaw = numericRaw.slice(0, settings.maxDigits);
                }

                let minus = cleaned.startsWith("-") ? "-" : "";
                let decimalPart = "";

                if (settings.decimal && cleaned.includes(".")) {
                    let parts = cleaned.split(".");
                    numericRaw = parts[0].replace(/\D/g, "");
                    decimalPart = "." + parts[1].replace(/\D/g, "");
                }

                cleaned = minus + numericRaw + decimalPart;

                if (settings.minValue !== null && parseNumber(cleaned) < settings.minValue) {
                    cleaned = String(settings.minValue);
                }
                if (settings.maxValue !== null && parseNumber(cleaned) > settings.maxValue) {
                    cleaned = String(settings.maxValue);
                }

                let result = cleaned;
                if (settings.separator) {
                    result = formatWithSeparator(result);
                }

                $input.val(result);
            });

            // KEYDOWN MASK HANDLER
            $input.on("keydown.numericInput", function (e) {
                if (!settings.mask) return;

                const el = this;
                const key = e.key;
                const caret = el.selectionStart;
                const selStart = el.selectionStart;
                const selEnd = el.selectionEnd;

                function isSlot(pos) {
                    return settings.mask[pos] === "#";
                }
                function prevSlot(pos) {
                    for (let i = pos - 1; i >= 0; i--) {
                        if (isSlot(i)) return i;
                    }
                    return null;
                }
                function nextSlot(pos) {
                    for (let i = pos; i < settings.mask.length; i++) {
                        if (isSlot(i)) return i;
                    }
                    return null;
                }

                const navKeys = ["ArrowLeft", "ArrowRight", "ArrowUp", "ArrowDown", "Home", "End", "Tab"];
                if (navKeys.includes(key) || e.ctrlKey || e.metaKey) return;

                if (key === "Backspace" || key === "Delete") {
                    e.preventDefault();
                    let val = $input.val().split("");

                    if (selStart !== selEnd) {
                        for (let i = selEnd - 1; i >= selStart; i--) {
                            if (isSlot(i)) val[i] = "";
                        }
                        $input.val(val.join(""));
                        el.setSelectionRange(selStart, selStart);
                        return;
                    }

                    if (key === "Backspace") {
                        let pos = prevSlot(caret);
                        if (pos !== null) {
                            val[pos] = "";
                            $input.val(val.join(""));
                            el.setSelectionRange(pos, pos);
                        }
                        return;
                    }

                    if (key === "Delete") {
                        let pos = nextSlot(caret);
                        if (pos !== null) {
                            val[pos] = "";
                            $input.val(val.join(""));
                            el.setSelectionRange(caret, caret);
                        }
                        return;
                    }
                }

                if (key?.length === 1 && !/\d/.test(key)) {
                    e.preventDefault();
                }
            });

            $input.on("focus.numericInput", function () {
                if (!settings.mask) {
                    let val = $input.val();
                    if (settings.prefix) val = val.replace(settings.prefix, "");
                    if (settings.suffix) val = val.replace(settings.suffix, "");
                    $input.val(val);
                }
            });

            $input.on("blur.numericInput", function () {
                if (!settings.mask) {
                    let val = $input.val().trim();
                    if (val === "") return;

                    if (settings.separator) {
                        val = val.replace(new RegExp("\\" + settings.separator, "g"), "");
                        val = formatWithSeparator(val);
                    }

                    val = settings.prefix + val + settings.suffix;
                    $input.val(val);
                }
            });
        });
    };

})(jQuery);