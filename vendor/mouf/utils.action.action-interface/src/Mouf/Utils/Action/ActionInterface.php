<?php 
namespace Mouf\Utils\Action;

/**
 * This interface is implemented by any object that represent an action that can be triggered.
 * An object implementing this interface will return a single value when the "val()" method is called.
 * Objects implementing this interface can perform one particular action and have been designed for that.
 * The action performed is completely up to the implementer (sending a mail, storing a result in database, displaying something on the screen...)
 * The action does not return anything, but can throw exceptions in case of errors.
 * 
 * @author David Négrier
 */
interface ActionInterface {
	/**
	 * Executes the action the object has been designed for.
	 * 
	 * @throws \Exception
	 */
	public function run();
}