Simple Logger

Компонент логгера для Yii Framework.
Основные отличия от встроенного в фреймворк логгера:
- настраиваемые уровни логгирования
- встроенные и возможность расширения классов formatter для лог сообщений
- встроенный обработчик (handler) для вывода все сообщений в консоль (для консольного приложения)


Основные части логгера:
SLogger - непосредственно логгер

Handlers - обработчики, осуществляеют необходимые операции с лог сообщениями,
например запись в файл, базу, отправка на email. Наследуются от класса SLoggerBaseHandler
Встроенные обработчики:
 - SLoggerFileHandler - запись лог сообщений в файл
 - SLoggerConsoleHandler - вывод лог сообщений в консоль
 - SLoggerMongoHandler - запись логов в mongo db

Formaters - форматировщики сообщений. Формируют сообщений в нужного вида для обработчиков.
Наследуются от класса SLoggerBaseFormater.
Встроенные форматтеры:
 - SLoggerDefaultFormatter - стандартная форматировка строк


Конфигурация
------------

```php
'components' => array(
    'slog' => array(
        'class' => 'Slogger',

        // дополнительные уровни логгирования
        'levels' => array('warning', 'fail'), // также возможна запись 'levels' => 'warning, fail',

        'handlers' => array(
            // файловый логгер
            array(
                'class' => 'SLoggerFileHandler',
                // форматтер лог сообщения
                'formatter' => array(
                    'class' => 'SLoggerDefaultFormater',
                    // формат даты
                    'dateFormat' => 'Y-m-d H:i:s',
                    // формат лог сообщения
                    'messageFormat' => '{date} [{level}]{level-spaces} {from} {message}',
                ),

                // уровни логгирования которые обрабатывает данный обработчик, по умолчанию все
                // можно задать свои уровни логгирования в виде массива или строки через запятую
                // также обработчик может обрабатывать уровни, которых нет в самом компоненте логгера
                // например 'levels' => array('trace', 'fullshit'),
                'levels' => '*',

                // файл по умолчанию
                'defaultFile' => 'blackhole',
                // расширение лог файлов по умолчанию (без точки)
                'extension' => 'log',
                // папка с лог файлами, по умолчанию runtime path
                'path' => 'application.runtime',
                // максимальный размер лог файла в KB, при превышении происходит ротация
                'maxFileSize' => 1024,
                // максимальное число одного лог файла, при превышении самый старый удаляется
                'maxFiles' => 10,
                // права доступа по умолчанию для создаваемых директорий лог файлов
                'directoryMode' => 0777,
            ),


            // логгер сообщений в консоль
            array(
                'class' => 'SLoggerConsoleHandler',
                // форматтер лог сообщения
                'formatter' => array(
                    'class' => 'SLoggerDefaultFormater',
                    // формат даты
                    'dateFormat' => 'Y-m-d H:i:s',
                    // формат лог сообщения
                    'messageFormat' => '{date} [{level}]{level-spaces} {from} {message}',
                ),
                // уровни логгирования которые обрабатывает данный обработчик, по умолчанию все
                // можно задать свои уровни логгирования в виде массива или строки через запятую
                // также обработчик может обрабатывать уровни, которых нет в самом компоненте логгера
                // например 'levels' => array('trace', 'fullshit'),
                'levels' => '*',

                // название константы, которая должна быть объявлена для консольного приложения
                'constant' => 'CONSOLE_APP',
            ),
        ),
    ),
),
```


Использование
-------------

Для использования логгера предоставлятся класс "сокращалка" L, в котором имеются функции шорткаты.

```php
// сообщение в лог файл по умолчанию blackhole.log
L::trace('Трассировочное сообщение');

// сообщение в лог файл events.log, константа __METHOD__ ипользуется, чтобы записать название функции источника сообщения
L::log('Событие 1', 'events', __METHOD__);

// сообщение которые будет записано в файл error.log в директорию fails (создастся автоматически)
L::fatal('Критическая ошибка', 'fails/error', __METHOD__);

```
