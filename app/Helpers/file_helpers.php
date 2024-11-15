<?php
define('TEST','123');
class FilePathEnum
{
    const PROFILE_IMAGE = "profile/";
   
}

class ProductSizeEnum
{
    const EXTRA_SMALL = "1";
    const SMALL = "2";
    const MEDIUM = "3";
    const LARGE = "4";
    const EXTRA_LARGE = "5";

    // const LIST = [
    //     "EXTRA_SMALL" =>1 ,
    //     "SMALL" =>2 ,
    //     "MEDIUM" =>3 ,
    //     "LARGE" =>4 ,
    //     "EXTRA_LARGE" =>5 ,
    // ]
}
class PaymentMethodEnum
{
    const COD = "1";
    const CARD = "2";
}
class PaymentStatusEnum
{
    const PENDING = "1";
    const SUCCESSFUL = "2";
    const FAILED = "3";
}
class ProductOrderStatusEnum
{
    const PENDING = "1";
    const COMPLETED = "2";
    const CANCELED = "3";
}
