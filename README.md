# Сообщение о нерабочих днях

> Для работы плагина необходима библиотека https://github.com/webmasterskaya/joomla-production-calendar

Плагин определяет, является ли сегодняшний день предпраздничным (сокращённым) или не рабочим (выходной/праздник) и добавляет в Joomla script options соответсвующее сообщение

- есть возможность назначить свои сообщения
- в сообщениях можно использовать языковые константы

## Пример получения сообщения
```js
let workingdays_message = Joomla?.getOptions('workingdays')?.message;
```

## Где использовать
Один из вариантов использования данного плагина - персонализация сообщений об отправке формы в [RadicalForm](https://github.com/Delo-Design/radicalform)

### Использование плагина с RadicalForm
В выходные дни хотелось бы указать посетителям сайта, отправившим форму, что их сообщение будет обработано не сразу же, а в ближайший рабочий день. 
В предпразднечный день, хочется указать клиентам, что день сокращённый и их сообщение могут не успеть обработать.
Для этого нужно добавить к сообщению, об успешной отправке формы RadicalForm, сообщение из Joomla script options
Обычно, вывод сообщения об удачной отправке формы, производится в поле `Код Javascript №2 (для вывода сообщения)`, поэтому туда нужно вставить следующий код:
```js
let workigdays = '';
try
{
	workigdays = Joomla?.getOptions('workingdays')?.message;
} catch(error){
	//do nothing
}
if(!!workigdays)
{
	rfMessage = workigdays + '<br>' + rfMessage
}
```

В совокупности с фреймворком Uikit 3, полный код показа собщения об удачной отправке формы, будет выглядеть так:
```js
let workigdays = '';
try
{
	workigdays = Joomla?.getOptions('workingdays')?.message;
} catch(error){
	//do nothing
}
if(!!workigdays)
{
	rfMessage = workigdays + '<br>' + rfMessage
}
UIkit.modal.alert(rfMessage);
```

По всем возникшим вопросам обращайтесь в Telegram-канал [https://t.me/projoomla](https://t.me/projoomla) - вам помогут 🙌