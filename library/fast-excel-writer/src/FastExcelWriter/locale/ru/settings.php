<?php
// ru_RU

return [
    'language' => 'Russian',
    'formats' => [
        'date' => 'DD.MM.YYYY',
        'time' => 'H:MM:SS',
        'datetime' => 'DD.MM.YYYY HH:MM:SS',
        'money' => '#,##0.00 [$₽];[RED]-#,##0.00 [$₽]',
    ],
    'functions' => [
        'ПОЛУЧИТЬ.ДАННЫЕ.СВОДНОЙ.ТАБЛИЦЫ' => 'GETPIVOTDATA', //Возвращает данные, хранящиеся в отчете сводной таблицы.
        'КУБЭЛЕМЕНТКИП' => 'CUBEKPIMEMBER', //Возвращает свойство ключевого индикатора производительности «(КИП)» и отображает имя «КИП» в ячейке. «КИП» представляет собой количественную величину, такую как ежемесячная валовая прибыль или ежеквартальная текучесть кадров, используемой для контроля эффективности работы организации.
        'КУБЭЛЕМЕНТ' => 'CUBEMEMBER', //Возвращает элемент или кортеж из куба. Используется для проверки существования элемента или кортежа в кубе.
        'КУБСВОЙСТВОЭЛЕМЕНТА' => 'CUBEMEMBERPROPERTY', //Возвращает значение свойства элемента из куба. Используется для проверки существования имени элемента в кубе и возвращает указанное свойство для этого элемента.
        'КУБПОРЭЛЕМЕНТ' => 'CUBERANKEDMEMBER', //Возвращает n-ый или ранжированный элемент в множество. Используется для возвращения одного или нескольких элементов в множество, например, лучшего продавца или 10 лучших студентов.
        'КУБМНОЖ' => 'CUBESET', //Определяет вычислительное множество элементов или кортежей, отправляя на сервер выражение, которое создает множество, а затем возвращает его в Microsoft Office Excel.
        'КУБЧИСЛОЭЛМНОЖ' => 'CUBESETCOUNT', //Возвращает число элементов множества.
        'КУБЗНАЧЕНИЕ' => 'CUBEVALUE', //Возвращает обобщенное значение из куба.
        'ДСРЗНАЧ' => 'DAVERAGE', //Возвращает среднее значение выбранных записей базы данных.
        'БСЧЁТ' => 'DCOUNT', //Подсчитывает количество числовых ячеек в базе данных.
        'БСЧЁТА' => 'DCOUNTA', //Подсчитывает количество непустых ячеек в базе данных.
        'БИЗВЛЕЧЬ' => 'DGET', //Извлекает из базы данных одну запись, удовлетворяющую заданному условию.
        'ДМАКС' => 'DMAX', //Возвращает максимальное значение среди выделенных записей базы данных.
        'ДМИН' => 'DMIN', //Возвращает минимальное значение среди выделенных записей базы данных.
        'БДПРОИЗВЕД' => 'DPRODUCT', //Перемножает значения определенного поля в записях базы данных, удовлетворяющих условию.
        'ДСТАНДОТКЛ' => 'DSTDEV', //Оценивает стандартное отклонение по выборке для выделенных записей базы данных.
        'ДСТАНДОТКЛП' => 'DSTDEVP', //Вычисляет стандартное отклонение по генеральной совокупности для выделенных записей базы данных
        'БДСУММ' => 'DSUM', //Суммирует числа в поле для записей базы данных, удовлетворяющих условию.
        'БДДИСП' => 'DVAR', //Оценивает дисперсию по выборке из выделенных записей базы данных
        'БДДИСПП' => 'DVARP', //Вычисляет дисперсию по генеральной совокупности для выделенных записей базы данных
        'ДАТА' => 'DATE', //Возвращает заданную дату в числовом формате.
        'ДАТАЗНАЧ' => 'DATEVALUE', //Преобразует дату из текстового формата в числовой формат.
        'ДЕНЬ' => 'DAY', //Преобразует дату в числовом формате в день месяца.
        'ДНЕЙ360' => 'DAYS360', //Вычисляет количество дней между двумя датами на основе 360-дневного года.
        'ДАТАМЕС' => 'EDATE', //Возвращает дату в числовом формате, отстоящую на заданное число месяцев вперед или назад от начальной даты.
        'КОНМЕСЯЦА' => 'EOMONTH', //Возвращает дату в числовом формате для последнего дня месяца, отстоящего вперед или назад на заданное число месяцев.
        'ЧАС' => 'HOUR', //Преобразует дату в числовом формате в часы.
        'МИНУТЫ' => 'MINUTE', //Преобразует дату в числовом формате в минуты.
        'МЕСЯЦ' => 'MONTH', //Преобразует дату в числовом формате в месяцы.
        'ЧИСТРАБДНИ' => 'NETWORKDAYS', //Возвращает количество рабочих дней между двумя датами.
        'ТДАТА' => 'NOW', //Возвращает текущую дату и время в числовом формате.
        'СЕКУНДЫ' => 'SECOND', //Преобразует дату в числовом формате в секунды.
        'ВРЕМЯ' => 'TIME', //Возвращает заданное время в числовом формате.
        'ВРЕМЗНАЧ' => 'TIMEVALUE', //Преобразует время из текстового формата в числовой формат.
        'СЕГОДНЯ' => 'TODAY', //Возвращает текущую дату в числовом формате.
        'ДЕНЬНЕД' => 'WEEKDAY', //Преобразует дату в числовом формате в день недели.
        'НОМНЕДЕЛИ' => 'WEEKNUM', //Преобразует числовое представление в число, которое указывает, на какую неделю года приходится указанная дата.
        'РАБДЕНЬ' => 'WORKDAY', //Возвращает дату в числовом формате, отстоящую вперед или назад на заданное количество рабочих дней.
        'ГОД' => 'YEAR', //Преобразует дату в числовом формате в год.
        'ДОЛЯГОДА' => 'YEARFRAC', //Возвращает долю года, которую составляет количество дней между начальной и конечной датами.
        'БЕССЕЛЬ.I' => 'BESSELI', //Возвращает модифицированную функцию Бесселя In(x).
        'БЕССЕЛЬ.J' => 'BESSELJ', //Возвращает функцию Бесселя Jn(x).
        'БЕССЕЛЬ.K' => 'BESSELK', //Возвращает модифицированную функцию Бесселя Kn(x).
        'БЕССЕЛЬ.Y' => 'BESSELY', //Возвращает функцию Бесселя Yn(x).
        'ДВ.В.ДЕС' => 'BIN2DEC', //Преобразует двоичное число в десятичное.
        'ДВ.В.ШЕСТН' => 'BIN2HEX', //Преобразует двоичное число в шестнадцатеричное.
        'ДВ.В.ВОСЬМ' => 'BIN2OCT', //Преобразует двоичное число в восьмеричное.
        'КОМПЛЕКСН' => 'COMPLEX', //Преобразует коэффициенты при вещественной и мнимой частях комплексного числа в комплексное число.
        'ПРЕОБР' => 'CONVERT', //Преобразует число из одной системы единиц измерения в другую.
        'ДЕС.В.ДВ' => 'DEC2BIN', //Преобразует десятичное число в двоичное.
        'ДЕС.В.ШЕСТН' => 'DEC2HEX', //Преобразует десятичное число в шестнадцатеричное.
        'ДЕС.В.ВОСЬМ' => 'DEC2OCT', //Преобразует десятичное число в восьмеричное.
        'ДЕЛЬТА' => 'DELTA', //Проверяет равенство двух значений.
        'ФОШ' => 'ERF', //Возвращает функцию ошибки.
        'ДФОШ' => 'ERFC', //Возвращает дополнительную функцию ошибки.
        'ПОРОГ' => 'GESTEP', //Проверяет, не превышает ли данное число порогового значения.
        'ШЕСТН.В.ДВ' => 'HEX2BIN', //Преобразует шестнадцатеричное число в двоичное.
        'ШЕСТН.В.ДЕС' => 'HEX2DEC', //Преобразует шестнадцатеричное число в десятичное.
        'ШЕСТН.В.ВОСЬМ' => 'HEX2OCT', //Преобразует шестнадцатеричное число в восьмеричное.
        'МНИМ.ABS' => 'IMABS', //Возвращает абсолютную величину (модуль) комплексного числа.
        'МНИМ.ЧАСТЬ' => 'IMAGINARY', //Возвращает коэффициент при мнимой части комплексного числа.
        'МНИМ.АРГУМЕНТ' => 'IMARGUMENT', //Возвращает значение аргумента комплексного числа (тета) — угол, выраженный в радианах.
        'МНИМ.СОПРЯЖ' => 'IMCONJUGATE', //Возвращает комплексно-сопряженное комплексное число.
        'МНИМ.COS' => 'IMCOS', //Возвращает косинус комплексного числа.
        'МНИМ.ДЕЛ' => 'IMDIV', //Возвращает частное от деления двух комплексных чисел.
        'МНИМ.EXP' => 'IMEXP', //Возвращает экспоненту комплексного числа.
        'МНИМ.LN' => 'IMLN', //Возвращает натуральный логарифм комплексного числа.
        'МНИМ.LOG10' => 'IMLOG10', //Возвращает обычный (десятичный) логарифм комплексного числа.
        'МНИМ.LOG2' => 'IMLOG2', //Возвращает двоичный логарифм комплексного числа.
        'МНИМ.СТЕПЕНЬ' => 'IMPOWER', //Возвращает комплексное число, возведенное в целую степень.
        'МНИМ.ПРОИЗВЕД' => 'IMPRODUCT', //Возвращает произведение от 2 до 29 комплексных чисел.
        'МНИМ.ВЕЩ' => 'IMREAL', //Возвращает коэффициент при вещественной части комплексного числа.
        'МНИМ.SIN' => 'IMSIN', //Возвращает синус комплексного числа.
        'МНИМ.КОРЕНЬ' => 'IMSQRT', //Возвращает значение квадратного корня из комплексного числа.
        'МНИМ.РАЗН' => 'IMSUB', //Возвращает разность двух комплексных чисел.
        'МНИМ.СУММ' => 'IMSUM', //Возвращает сумму комплексных чисел.
        'ВОСЬМ.В.ДВ' => 'OCT2BIN', //Преобразует восьмеричное число в двоичное.
        'ВОСЬМ.В.ДЕС' => 'OCT2DEC', //Преобразует восьмеричное число в десятичное.
        'ВОСЬМ.В.ШЕСТН' => 'OCT2HEX', //Преобразует восьмеричное число в шестнадцатеричное.
        'НАКОПДОХОД' => 'ACCRINT', //Возвращает накопленный процент по ценным бумагам с периодической выплатой процентов.
        'НАКОПДОХОДПОГАШ' => 'ACCRINTM', //Возвращает накопленный процент по ценным бумагам, проценты по которым выплачиваются в срок погашения.
        'АМОРУМ' => 'AMORDEGRC', //Возвращает величину амортизации для каждого периода, используя коэффициент амортизации.
        'АМОРУВ' => 'AMORLINC', //Возвращает величину амортизации для каждого периода.
        'ДНЕЙКУПОНДО' => 'COUPDAYBS', //Возвращает количество дней от начала действия купона до даты соглашения.
        'ДНЕЙКУПОН' => 'COUPDAYS', //Возвращает число дней в периоде купона, содержащем дату соглашения.
        'ДНЕЙКУПОНПОСЛЕ' => 'COUPDAYSNC', //Возвращает число дней от даты соглашения до срока следующего купона.
        'ДАТАКУПОНПОСЛЕ' => 'COUPNCD', //Возвращает следующую дату купона после даты соглашения.
        'ЧИСЛКУПОН' => 'COUPNUM', //Возвращает количество купонов, которые могут быть оплачены между датой соглашения и сроком вступления в силу.
        'ДАТАКУПОНДО' => 'COUPPCD', //Возвращает предыдущую дату купона перед датой соглашения.
        'ОБЩПЛАТ' => 'CUMIPMT', //Возвращает общую выплату, произведенную между двумя периодическими выплатами.
        'ОБЩДОХОД' => 'CUMPRINC', //Возвращает общую выплату по займу между двумя периодами.
        'ФУО' => 'DB', //Возвращает величину амортизации актива для заданного периода, рассчитанную методом фиксированного уменьшения остатка.
        'ДДОБ' => 'DDB', //Возвращает величину амортизации актива за данный период, используя метод двойного уменьшения остатка или иной явно указанный метод.
        'СКИДКА' => 'DISC', //Возвращает норму скидки для ценных бумаг.
        'РУБЛЬ.ДЕС' => 'DOLLARDE', //Преобразует цену в рублях, выраженную в виде дроби, в цену в рублях, выраженную десятичным числом.
        'РУБЛЬ.ДРОБЬ' => 'DOLLARFR', //Преобразует цену в рублях, выраженную десятичным числом, в цену в рублях, выраженную в виде дроби.
        'ДЛИТ' => 'DURATION', //Возвращает ежегодную продолжительность действия ценных бумаг с периодическими выплатами по процентам.
        'ЭФФЕКТ' => 'EFFECT', //Возвращает действующие ежегодные процентные ставки.
        'БС' => 'FV', //Возвращает будущую стоимость инвестиции.
        'БЗРАСПИС' => 'FVSCHEDULE', //Возвращает будущую стоимость первоначальной основной суммы после начисления ряда сложных процентов.
        'ИНОРМА' => 'INTRATE', //Возвращает процентную ставку для полностью инвестированных ценных бумаг.
        'ПРПЛТ' => 'IPMT', //Возвращает величину выплаты прибыли на вложения за данный период.
        'ВСД' => 'IRR', //Возвращает внутреннюю ставку доходности для ряда потоков денежных средств.
        'ПРОЦПЛАТ' => 'ISPMT', //Вычисляет выплаты за указанный период инвестиции.
        'МДЛИТ' => 'MDURATION', //Возвращает модифицированную длительность Маколея для ценных бумаг с предполагаемой номинальной стоимостью 100 рублей.
        'МВСД' => 'MIRR', //Возвращает внутреннюю ставку доходности, при которой положительные и отрицательные денежные потоки имеют разные значения ставки.
        'НОМИНАЛ' => 'NOMINAL', //Возвращает номинальную годовую процентную ставку.
        'КПЕР' => 'NPER', //Возвращает общее количество периодов выплаты для данного вклада.
        'ЧПС' => 'NPV', //Возвращает чистую приведенную стоимость инвестиции, основанной на серии периодических денежных потоков и ставке дисконтирования.
        'ЦЕНАПЕРВНЕРЕГ' => 'ODDFPRICE', //Возвращает цену за 100 рублей нарицательной стоимости ценных бумаг с нерегулярным первым периодом.
        'ДОХОДПЕРВНЕРЕГ' => 'ODDFYIELD', //Возвращает доход по ценным бумагам с нерегулярным первым периодом.
        'ЦЕНАПОСЛНЕРЕГ' => 'ODDLPRICE', //Возвращает цену за 100 рублей нарицательной стоимости ценных бумаг с нерегулярным последним периодом.
        'ДОХОДПОСЛНЕРЕГ' => 'ODDLYIELD', //Возвращает доход по ценным бумагам с нерегулярным последним периодом.
        'ПЛТ' => 'PMT', //Возвращает величину выплаты за один период аннуитета.
        'ОСПЛТ' => 'PPMT', //Возвращает величину выплат в погашение основной суммы по инвестиции за заданный период.
        'ЦЕНА' => 'PRICE', //Возвращает цену за 100 рублей нарицательной стоимости ценных бумаг, по которым производится периодическая выплата процентов.
        'ЦЕНАСКИДКА' => 'PRICEDISC', //Возвращает цену за 100 рублей номинальной стоимости ценных бумаг, на которые сделана скидка.
        'ЦЕНАПОГАШ' => 'PRICEMAT', //Возвращает цену за 100 рублей номинальной стоимости ценных бумаг, проценты по которым выплачиваются в срок погашения.
        'ПС' => 'PV', //Возвращает приведенную (к текущему моменту) стоимость инвестиции.
        'СТАВКА' => 'RATE', //Возвращает процентную ставку по аннуитету за один период.
        'ПОЛУЧЕНО' => 'RECEIVED', //Возвращает сумму, полученную к сроку погашения полностью обеспеченных ценных бумаг.
        'АПЛ' => 'SLN', //Возвращает величину линейной амортизации актива за один период.
        'АСЧ' => 'SYD', //Возвращает величину амортизации актива за данный период, рассчитанную методом суммы годовых чисел.
        'РАВНОКЧЕК' => 'TBILLEQ', //Возвращает эквивалентный облигации доход по казначейскому чеку.
        'ЦЕНАКЧЕК' => 'TBILLPRICE', //Возвращает цену за 100 рублей нарицательной стоимости для казначейского чека.
        'ДОХОДКЧЕК' => 'TBILLYIELD', //Возвращает доход по казначейскому чеку.
        'ПУО' => 'VDB', //Возвращает величину амортизации актива для указанного или частичного периода при использовании метода сокращающегося баланса.
        'ЧИСТВНДОХ' => 'XIRR', //Возвращает внутреннюю ставку доходности для графика денежных потоков, которые не обязательно носят периодический характер.
        'ЧИСТНЗ' => 'XNPV', //Возвращает чистую приведенную стоимость для денежных потоков, которые не обязательно являются периодическими.
        'ДОХОД' => 'YIELD', //Возвращает доход от ценных бумаг, по которым производятся периодические выплаты процентов.
        'ДОХОДСКИДКА' => 'YIELDDISC', //Возвращает годовой доход по ценным бумагам, на которые сделана скидка (пример — казначейские чеки).
        'ДОХОДПОГАШ' => 'YIELDMAT', //Возвращает годовой доход от ценных бумаг, проценты по которым выплачиваются в срок погашения.
        'ЯЧЕЙКА' => 'CELL', //Возвращает информацию о формате, расположении или содержимом ячейки.
        'ТИП.ОШИБКИ' => 'ERROR.TYPE', //Возвращает числовой код, соответствующий типу ошибки.
        'ИНФОРМ' => 'INFO', //Возвращает информацию о текущей операционной среде.
        'ЕПУСТО' => 'ISBLANK', //Возвращает значение ИСТИНА, если аргумент является ссылкой на пустую ячейку.
        'ЕОШ' => 'ISERR', //Возвращает значение ИСТИНА, если аргумент ссылается на любое значение ошибки, кроме #Н/Д.
        'ЕОШИБКА' => 'ISERROR', //Возвращает значение ИСТИНА, если аргумент ссылается на любое значение ошибки.
        'ЕЧЁТН' => 'ISEVEN', //Возвращает значение ИСТИНА, если значение аргумента является четным числом.
        'ЕЛОГИЧ' => 'ISLOGICAL', //Возвращает значение ИСТИНА, если аргумент ссылается на логическое значение.
        'ЕНД' => 'ISNA', //Возвращает значение ИСТИНА, если аргумент ссылается на значение ошибки #Н/Д.
        'ЕНЕТЕКСТ' => 'ISNONTEXT', //Возвращает значение ИСТИНА, если значение аргумента не является текстом.
        'ЕЧИСЛО' => 'ISNUMBER', //Возвращает значение ИСТИНА, если аргумент ссылается на число.
        'ЕНЕЧЁТ' => 'ISODD', //Возвращает значение ИСТИНА, если значение аргумента является нечетным числом.
        'ЕССЫЛКА' => 'ISREF', //Возвращает значение ИСТИНА, если значение аргумента является ссылкой.
        'ЕТЕКСТ' => 'ISTEXT', //Возвращает значение ИСТИНА, если значение аргумента является текстом.
        'Ч' => 'N', //Возвращает значение, преобразованное в число.
        'НД' => 'NA', //Возвращает значение ошибки #Н/Д.
        'ТИП' => 'TYPE', //Возвращает число, обозначающее тип данных значения.
        'И' => 'AND', //Renvoie VRAI si tous ses arguments sont VRAI.
        'ЛОЖЬ' => 'FALSE', //Возвращает логическое значение ЛОЖЬ.
        'ЕСЛИ' => 'IF', //Выполняет проверку условия.
        'ЕСЛИОШИБКА' => 'IFERROR', //Возвращает введённое значение, если вычисление по формуле вызывает ошибку; в противном случае функция возвращает результат вычисления.
        'НЕ' => 'NOT', //Меняет логическое значение своего аргумента на противоположное.
        'ИЛИ' => 'OR', //Возвращает значение ИСТИНА, если хотя бы один аргумент имеет значение ИСТИНА.
        'ИСТИНА' => 'TRUE', //Возвращает логическое значение ИСТИНА.
        'АДРЕС' => 'ADDRESS', //Возвращает ссылку на отдельную ячейку листа в виде текста.
        'ОБЛАСТИ' => 'AREAS', //Возвращает количество областей в ссылке.
        'ВЫБОР' => 'CHOOSE', //Выбирает значение из списка значений по индексу.
        'СТОЛБЕЦ' => 'COLUMN', //Возвращает номер столбца, на который указывает ссылка.
        'ЧИСЛСТОЛБ' => 'COLUMNS', //Возвращает количество столбцов в ссылке.
        'ГПР' => 'HLOOKUP', //Ищет в первой строке массива и возвращает значение отмеченной ячейки
        'ГИПЕРССЫЛКА' => 'HYPERLINK', //Создает ссылку, открывающую документ, который находится на сервере сети, в интрасети или в Интернете.
        'ИНДЕКС' => 'INDEX', //Использует индекс для выбора значения из ссылки или массива.
        'ДВССЫЛ' => 'INDIRECT', //Возвращает ссылку, заданную текстовым значением.
        'ПРОСМОТР' => 'LOOKUP', //Ищет значения в векторе или массиве.
        'ПОИСКПОЗ' => 'MATCH', //Ищет значения в ссылке или массиве.
        'СМЕЩ' => 'OFFSET', //Возвращает смещение ссылки относительно заданной ссылки.
        'СТРОКА' => 'ROW', //Возвращает номер строки, определяемой ссылкой.
        'ЧСТРОК' => 'ROWS', //Возвращает количество строк в ссылке.
        'ДРВ' => 'RTD', //Извлекает данные реального времени из программ, поддерживающих автоматизацию COM (Программирование объектов. Стандартное средство для работы с объектами некоторого приложения из другого приложения или средства разработки. Программирование объектов (ранее называемое программированием OLE) является функцией модели COM (Component Object Model, модель компонентных объектов).).
        'ТРАНСП' => 'TRANSPOSE', //Возвращает транспонированный массив.
        'ВПР' => 'VLOOKUP', //Ищет значение в первом столбце массива и возвращает значение из ячейки в найденной строке и указанном столбце.
        'ABS' => 'ABS', //Возвращает модуль (абсолютную величину) числа.
        'ACOS' => 'ACOS', //Возвращает арккосинус числа.
        'ACOSH' => 'ACOSH', //Возвращает гиперболический арккосинус числа.
        'ASIN' => 'ASIN', //Возвращает арксинус числа.
        'ASINH' => 'ASINH', //Возвращает гиперболический арксинус числа.
        'ATAN' => 'ATAN', //Возвращает арктангенс числа.
        'ATAN2' => 'ATAN2', //Возвращает арктангенс для заданных координат x и y.
        'ATANH' => 'ATANH', //Возвращает гиперболический арктангенс числа.
        'ОКРВВЕРХ' => 'CEILING', //Округляет число до ближайшего целого или до ближайшего кратного указанному значению.
        'ЧИСЛКОМБ' => 'COMBIN', //Возвращает количество комбинаций для заданного числа объектов.
        'COS' => 'COS', //Возвращает косинус числа.
        'COSH' => 'COSH', //Возвращает гиперболический косинус числа.
        'ГРАДУСЫ' => 'DEGREES', //Преобразует радианы в градусы.
        'ЧЁТН' => 'EVEN', //Округляет число до ближайшего четного целого.
        'EXP' => 'EXP', //Возвращает число e, возведенное в указанную степень.
        'ФАКТР' => 'FACT', //Возвращает факториал числа.
        'ДВФАКТР' => 'FACTDOUBLE', //Возвращает двойной факториал числа.
        'ОКРВНИЗ' => 'FLOOR', //Округляет число до ближайшего меньшего по модулю значения.
        'НОД' => 'GCD', //Возвращает наибольший общий делитель.
        'ЦЕЛОЕ' => 'INT', //Округляет число до ближайшего меньшего целого.
        'НОК' => 'LCM', //Возвращает наименьшее общее кратное.
        'LN' => 'LN', //Возвращает натуральный логарифм числа.
        'LOG' => 'LOG', //Возвращает логарифм числа по заданному основанию.
        'LOG10' => 'LOG10', //Возвращает десятичный логарифм числа.
        'МОПРЕД' => 'MDETERM', //Возвращает определитель матрицы массива.
        'МОБР' => 'MINVERSE', //Возвращает обратную матрицу массива.
        'МУМНОЖ' => 'MMULT', //Возвращает произведение матриц двух массивов.
        'ОСТАТ' => 'MOD', //Возвращает остаток от деления.
        'ОКРУГЛТ' => 'MROUND', //Возвращает число, округленное с требуемой точностью.
        'МУЛЬТИНОМ' => 'MULTINOMIAL', //Возвращает мультиномиальный коэффициент множества чисел.
        'НЕЧЁТ' => 'ODD', //Округляет число до ближайшего нечетного целого.
        'ПИ' => 'PI', //Возвращает число пи.
        'СТЕПЕНЬ' => 'POWER', //Возвращает результат возведения числа в степень.
        'ПРОИЗВЕД' => 'PRODUCT', //Возвращает произведение аргументов.
        'ЧАСТНОЕ' => 'QUOTIENT', //Возвращает целую часть частного при делении.
        'РАДИАНЫ' => 'RADIANS', //Преобразует градусы в радианы.
        'СЛЧИС' => 'RAND', //Возвращает случайное число в интервале от 0 до 1.
        'СЛУЧМЕЖДУ' => 'RANDBETWEEN', //Возвращает случайное число в интервале между двумя заданными числами.
        'РИМСКОЕ' => 'ROMAN', //Преобразует арабские цифры в римские в виде текста.
        'ОКРУГЛ' => 'ROUND', //Округляет число до указанного количества десятичных разрядов.
        'ОКРУГЛВНИЗ' => 'ROUNDDOWN', //Округляет число до ближайшего меньшего по модулю значения.
        'ОКРУГЛВВЕРХ' => 'ROUNDUP', //Округляет число до ближайшего большего по модулю значения.
        'РЯД.СУММ' => 'SERIESSUM', //Возвращает сумму степенного ряда, вычисленную по формуле.
        'ЗНАК' => 'SIGN', //Возвращает знак числа.
        'SIN' => 'SIN', //Возвращает синус заданного угла.
        'SINH' => 'SINH', //Возвращает гиперболический синус числа.
        'КОРЕНЬ' => 'SQRT', //Возвращает положительное значение квадратного корня.
        'КОРЕНЬПИ' => 'SQRTPI', //Возвращает квадратный корень из значения выражения (число * ПИ).
        'ПРОМЕЖУТОЧНЫЕ.ИТОГИ' => 'SUBTOTAL', //Возвращает промежуточный итог в списке или базе данных.
        'СУММ' => 'SUM', //Суммирует аргументы.
        'СУММЕСЛИ' => 'SUMIF', //Суммирует ячейки, удовлетворяющие заданному условию.
        'СУММЕСЛИМН' => 'SUMIFS', //Суммирует диапазон ячеек, удовлетворяющих нескольким условиям.
        'СУММПРОИЗВ' => 'SUMPRODUCT', //Возвращает сумму произведений соответствующих элементов массивов.
        'СУММКВ' => 'SUMSQ', //Возвращает сумму квадратов аргументов.
        'СУММРАЗНКВ' => 'SUMX2MY2', //Возвращает сумму разностей квадратов соответствующих значений в двух массивах.
        'СУММСУММКВ' => 'SUMX2PY2', //Возвращает сумму сумм квадратов соответствующих элементов двух массивов.
        'СУММКВРАЗН' => 'SUMXMY2', //Возвращает сумму квадратов разностей соответствующих значений в двух массивах.
        'TAN' => 'TAN', //Возвращает тангенс числа.
        'TANH' => 'TANH', //Возвращает гиперболический тангенс числа.
        'ОТБР' => 'TRUNC', //Отбрасывает дробную часть числа.
        'СРОТКЛ' => 'AVEDEV', //Возвращает среднее арифметическое абсолютных значений отклонений точек данных от среднего.
        'СРЗНАЧ' => 'AVERAGE', //Возвращает среднее арифметическое аргументов.
        'СРЗНАЧА' => 'AVERAGEA', //Возвращает среднее арифметическое аргументов, включая числа, текст и логические значения.
        'СРЗНАЧЕСЛИ' => 'AVERAGEIF', //Возвращает среднее значение (среднее арифметическое) всех ячеек в диапазоне, которые удовлетворяют данному условию.
        'СРЗНАЧЕСЛИМН' => 'AVERAGEIFS', //Возвращает среднее значение (среднее арифметическое) всех ячеек, которые удовлетворяют нескольким условиям.
        'БЕТАРАСП' => 'BETADIST', //Возвращает интегральную функцию бета-распределения.
        'БЕТАОБР' => 'BETAINV', //Возвращает обратную интегральную функцию указанного бета-распределения.
        'БИНОМРАСП' => 'BINOMDIST', //Возвращает отдельное значение биномиального распределения.
        'ХИ2РАСП' => 'CHIDIST', //Возвращает одностороннюю вероятность распределения хи-квадрат.
        'ХИ2ОБР' => 'CHIINV', //Возвращает обратное значение односторонней вероятности распределения хи-квадрат.
        'ХИ2ТЕСТ' => 'CHITEST', //Возвращает тест на независимость.
        'ДОВЕРИТ' => 'CONFIDENCE', //Возвращает доверительный интервал для среднего значения по генеральной совокупности.
        'КОРРЕЛ' => 'CORREL', //Возвращает коэффициент корреляции между двумя множествами данных.
        'СЧЁТ' => 'COUNT', //Подсчитывает количество чисел в списке аргументов.
        'СЧЁТЗ' => 'COUNTA', //Подсчитывает количество значений в списке аргументов.
        'СЧИТАТЬПУСТОТЫ' => 'COUNTBLANK', //Подсчитывает количество пустых ячеек в диапазоне
        'СЧЁТЕСЛИ' => 'COUNTIF', //Подсчитывает количество ячеек в диапазоне, удовлетворяющих заданному условию
        'СЧЁТЕСЛИМН' => 'COUNTIFS', //Подсчитывает количество ячеек внутри диапазона, удовлетворяющих нескольким условиям.
        'КОВАР' => 'COVAR', //Возвращает ковариацию, среднее произведений парных отклонений
        'КРИТБИНОМ' => 'CRITBINOM', //Возвращает наименьшее значение, для которого интегральное биномиальное распределение меньше или равно заданному критерию.
        'КВАДРОТКЛ' => 'DEVSQ', //Возвращает сумму квадратов отклонений.
        'ЭКСПРАСП' => 'EXPONDIST', //Возвращает экспоненциальное распределение.
        'FРАСП' => 'FDIST', //Возвращает F-распределение вероятности.
        'FРАСПОБР' => 'FINV', //Возвращает обратное значение для F-распределения вероятности.
        'ФИШЕР' => 'FISHER', //Возвращает преобразование Фишера.
        'ФИШЕРОБР' => 'FISHERINV', //Возвращает обратное преобразование Фишера.
        'ПРЕДСКАЗ' => 'FORECAST', //Возвращает значение линейного тренда.
        'ЧАСТОТА' => 'FREQUENCY', //Возвращает распределение частот в виде вертикального массива.
        'ФТЕСТ' => 'FTEST', //Возвращает результат F-теста.
        'ГАММАРАСП' => 'GAMMADIST', //Возвращает гамма-распределение.
        'ГАММАОБР' => 'GAMMAINV', //Возвращает обратное гамма-распределение.
        'ГАММАНЛОГ' => 'GAMMALN', //Возвращает натуральный логарифм гамма функции, Γ(x).
        'СРГЕОМ' => 'GEOMEAN', //Возвращает среднее геометрическое.
        'РОСТ' => 'GROWTH', //Возвращает значения в соответствии с экспоненциальным трендом.
        'СРГАРМ' => 'HARMEAN', //Возвращает среднее гармоническое.
        'ГИПЕРГЕОМЕТ' => 'HYPGEOMDIST', //Возвращает гипергеометрическое распределение.
        'ОТРЕЗОК' => 'INTERCEPT', //Возвращает отрезок, отсекаемый на оси линией линейной регрессии.
        'ЭКСЦЕСС' => 'KURT', //Возвращает эксцесс множества данных.
        'НАИБОЛЬШИЙ' => 'LARGE', //Возвращает k-ое наибольшее значение в множестве данных.
        'ЛИНЕЙН' => 'LINEST', //Возвращает параметры линейного тренда.
        'ЛГРФПРИБЛ' => 'LOGEST', //Возвращает параметры экспоненциального тренда.
        'ЛОГНОРМОБР' => 'LOGINV', //Возвращает обратное логарифмическое нормальное распределение.
        'ЛОГНОРМРАСП' => 'LOGNORMDIST', //Возвращает интегральное логарифмическое нормальное распределение.
        'МАКС' => 'MAX', //Возвращает наибольшее значение в списке аргументов.
        'МАКСА' => 'MAXA', //Возвращает наибольшее значение в списке аргументов, включая числа, текст и логические значения.
        'МЕДИАНА' => 'MEDIAN', //Возвращает медиану заданных чисел.
        'МИН' => 'MIN', //Возвращает наименьшее значение в списке аргументов.
        'МИНА' => 'MINA', //Возвращает наименьшее значение в списке аргументов, включая числа, текст и логические значения.
        'МОДА' => 'MODE', //Возвращает значение моды множества данных.
        'ОТРБИНОМРАСП' => 'NEGBINOMDIST', //Возвращает отрицательное биномиальное распределение.
        'НОРМРАСП' => 'NORMDIST', //Возвращает нормальную функцию распределения.
        'НОРМОБР' => 'NORMINV', //Возвращает обратное нормальное распределение.
        'НОРМСТРАСП' => 'NORMSDIST', //Возвращает стандартное нормальное интегральное распределение.
        'НОРМСТОБР' => 'NORMSINV', //Возвращает обратное значение стандартного нормального распределения.
        'ПИРСОН' => 'PEARSON', //Возвращает коэффициент корреляции Пирсона.
        'ПЕРСЕНТИЛЬ' => 'PERCENTILE', //Возвращает k-ую персентиль для значений диапазона.
        'ПРОЦЕНТРАНГ' => 'PERCENTRANK', //Возвращает процентную норму значения в множестве данных.
        'ПЕРЕСТ' => 'PERMUT', //Возвращает количество перестановок для заданного числа объектов.
        'ПУАССОН' => 'POISSON', //Возвращает распределение Пуассона.
        'ВЕРОЯТНОСТЬ' => 'PROB', //Возвращает вероятность того, что значение из диапазона находится внутри заданных пределов.
        'КВАРТИЛЬ' => 'QUARTILE', //Возвращает квартиль множества данных.
        'РАНГ' => 'RANK', //Возвращает ранг числа в списке чисел.
        'КВПИРСОН' => 'RSQ', //Возвращает квадрат коэффициента корреляции Пирсона.
        'СКОС' => 'SKEW', //Возвращает асимметрию распределения.
        'НАКЛОН' => 'SLOPE', //Возвращает наклон линии линейной регрессии.
        'НАИМЕНЬШИЙ' => 'SMALL', //Возвращает k-ое наименьшее значение в множестве данных.
        'НОРМАЛИЗАЦИЯ' => 'STANDARDIZE', //Возвращает нормализованное значение.
        'СТАНДОТКЛОН' => 'STDEV', //Оценивает стандартное отклонение по выборке.
        'СТАНДОТКЛОНА' => 'STDEVA', //Оценивает стандартное отклонение по выборке, включая числа, текст и логические значения.
        'СТАНДОТКЛОНП' => 'STDEVP', //Вычисляет стандартное отклонение по генеральной совокупности.
        'СТАНДОТКЛОНПА' => 'STDEVPA', //Вычисляет стандартное отклонение по генеральной совокупности, включая числа, текст и логические значения.
        'СТОШYX' => 'STEYX', //Возвращает стандартную ошибку предсказанных значений y для каждого значения x в регрессии.
        'СТЬЮДРАСП' => 'TDIST', //Возвращает t-распределение Стьюдента.
        'СТЬЮДРАСПОБР' => 'TINV', //Возвращает обратное t-распределение Стьюдента.
        'ТЕНДЕНЦИЯ' => 'TREND', //Возвращает значения в соответствии с линейным трендом.
        'УРЕЗСРЕДНЕЕ' => 'TRIMMEAN', //Возвращает среднее внутренности множества данных.
        'ТТЕСТ' => 'TTEST', //Возвращает вероятность, соответствующую критерию Стьюдента.
        'ДИСП' => 'VAR', //Оценивает дисперсию по выборке.
        'ДИСПА' => 'VARA', //Оценивает дисперсию по выборке, включая числа, текст и логические значения.
        'ДИСПР' => 'VARP', //Вычисляет дисперсию для генеральной совокупности.
        'ДИСПРА' => 'VARPA', //Вычисляет дисперсию для генеральной совокупности, включая числа, текст и логические значения.
        'ВЕЙБУЛЛ' => 'WEIBULL', //Возвращает распределение Вейбулла.
        'ZТЕСТ' => 'ZTEST', //Возвращает двустороннее P-значение z-теста.
        'ASC' => 'ASC', //Для языков с двухбайтовыми наборами знаков (например, катакана) преобразует полноширинные (двухбайтовые) знаки в полуширинные (однобайтовые).
        'БАТТЕКСТ' => 'BAHTTEXT', //Преобразует число в текст, используя денежный формат ß (БАТ).
        'СИМВОЛ' => 'CHAR', //Возвращает знак с заданным кодом.
        'ПЕЧСИМВ' => 'CLEAN', //Удаляет все непечатаемые знаки из текста.
        'КОДСИМВ' => 'CODE', //Возвращает числовой код первого знака в текстовой строке.
        'СЦЕПИТЬ' => 'CONCATENATE', //Объединяет несколько текстовых элементов в один.
        'РУБЛЬ' => 'DOLLAR', //Преобразует число в текст, используя денежный формат.
        'СОВПАД' => 'EXACT', //Проверяет идентичность двух текстовых значений.
        'НАЙТИ' => 'FIND', //Ищет вхождения одного текстового значения в другом (с учетом регистра).
        'НАЙТИБ' => 'FINDB', //Ищет вхождения одного текстового значения в другом (с учетом регистра).
        'ФИКСИРОВАННЫЙ' => 'FIXED', //Форматирует число и преобразует его в текст с заданным числом десятичных знаков.
        'JIS' => 'JIS', //Для языков с двухбайтовыми наборами знаков (например, катакана) преобразует полуширинные (однобайтовые) знаки в текстовой строке в полноширинные (двухбайтовые).
        'ЛЕВСИМВ' => 'LEFT', //Возвращает крайние слева знаки текстового значения.
        'ЛЕВБ' => 'LEFTB', //Возвращает крайние слева знаки текстового значения.
        'ДЛСТР' => 'LEN', //Возвращает количество знаков в текстовой строке.
        'ДЛИНБ' => 'LENB', //Возвращает количество знаков в текстовой строке.
        'СТРОЧН' => 'LOWER', //Преобразует все буквы текста в строчные.
        'ПСТР' => 'MID', //Возвращает заданное число знаков из строки текста, начиная с указанной позиции.
        'ПСТРБ' => 'MIDB', //Возвращает заданное число знаков из строки текста, начиная с указанной позиции.
        'PHONETIC' => 'PHONETIC', //Извлекает фонетические (фуригана) знаки из текстовой строки.
        'ПРОПНАЧ' => 'PROPER', //Преобразует первую букву в каждом слове текста в прописную.
        'ЗАМЕНИТЬ' => 'REPLACE', //Заменяет знаки в тексте.
        'ЗАМЕНИТЬБ' => 'REPLACEB', //Заменяет знаки в тексте.
        'ПОВТОР' => 'REPT', //Повторяет текст заданное число раз.
        'ПРАВСИМВ' => 'RIGHT', //Возвращает крайние справа знаки текстовой строки.
        'ПРАВБ' => 'RIGHTB', //Возвращает крайние справа знаки текстовой строки.
        'ПОИСК' => 'SEARCH', //Ищет вхождения одного текстового значения в другом (без учета регистра).
        'ПОИСКБ' => 'SEARCHB', //Ищет вхождения одного текстового значения в другом (без учета регистра).
        'ПОДСТАВИТЬ' => 'SUBSTITUTE', //Заменяет в текстовой строке старый текст новым.
        'Т' => 'T', //Преобразует аргументы в текст.
        'ТЕКСТ' => 'TEXT', //Форматирует число и преобразует его в текст.
        'СЖПРОБЕЛЫ' => 'TRIM', //Удаляет из текста пробелы.
        'ПРОПИСН' => 'UPPER', //Преобразует все буквы текста в прописные.
        'ЗНАЧЕН' => 'VALUE', //Преобразует текстовый аргумент в число.
    ],
];
//EOF
