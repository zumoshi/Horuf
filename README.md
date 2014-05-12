Horuf
=====

Classes to Convert numbers to Words (تبدیل عدد به حروف)

---

##Usage:
---
    $horuf = new horuf();
    echo $horuf->convert(568); //returns پانصد و شصت و هشت

it can also give you Ordinal (ترتیبی) numbers

    $horuf = new horuf('farsi',horuf::Ordinal);
    echo $horuf->convert(1); //returns اول
or

    $horuf = new horuf('farsi',horuf::Ordinal2);
    echo $horuf->convert(1); //returns اولین
will work with big numbers too:

    echo $horuf->convert(190095323);
    //returns صد و نود میلیون و نود و پنج هزار و سیصد و بیست و سه