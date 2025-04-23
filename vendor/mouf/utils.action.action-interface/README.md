What is this package
====================

This package contains an interface used by many objects to declare they can perform an action.
Objects implementing this interface can perform one particular action and have been designed for that.
The action performed is completely up to the implementer (sending a mail, storing a result in database, displaying something on the screen...) The concept is very simple, and very powerful at the same time.

This is a very simple and very powerful system.

```php
namespace Mouf\Utils\Action;

interface ActionInterface {
	/**
	 * Executes the action the object has been designed for.
	 * 
	 * @throws \Exception
	 */
	public function run();
}
```

Here are a few samples:

- a class that sends one mail
- a class that outputs some HTML
- a class that displays an error if the user does not have the correct right
- ...

Mouf package
------------

This package is part of Mouf (http://mouf-php.com), an effort to ensure good developing practices by providing a graphical dependency injection framework.
