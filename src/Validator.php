<?php
namespace Ayeo\Alligator;

class Validator
{
    private $errors = [];
    private $invalidFields = [];

    public function __construct(?string $version = null)
    {
        if (is_null($version)) {

        } else {
            $this->translator = new Translator(); //todo: depends on version
        }

    }

    public function validate($object, $rules)
    {
        if (isset($this->translator)) {
            $rules = $this->translator->translate($rules);
        }

        $errors = [];
        foreach ($rules as $fieldName => $rule) {
            $this->processValidation($rule, $fieldName, $object, $errors);
        }
        $this->errors = $errors;

        return count($this->getErrors()) === 0;
    }

    private function processValidation($rule, string $fieldName, $object, array &$errors = [])
    {
        if (isset($errors[$fieldName]) === false) {
            $errors[$fieldName] = [];
        }

        if (is_array($rule)) {
            foreach ($rule as $xFieldName => $xRule) {
                if (isset($errors[$fieldName]) === false) {
                    $errors[$fieldName] = [];
                }

                if ($xRule instanceof Conditional) {
                    $zbychu = $xRule;
                    $nestedObject = $this->getFieldValue($fieldName, $object);
                    $a = $this->getFieldValue($zbychu->getFieldName(), $object);
                    $b = $zbychu->getExpectedValue();
                    if ($a == $b) {
                        foreach ($zbychu->getRules() as $yy => $xxx) {
                            if ($yy === '*') {
                                foreach (get_object_vars((object)$nestedObject) as $propertyName => $value) {
                                    $this->processObjectValidation(
                                        $xxx,
                                        $propertyName,
                                        (object)$nestedObject,
                                        $errors[$fieldName]
                                    );
                                }
                            } elseif ($yy === '') {
                                if (is_null($nestedObject)) {
                                    continue;
                                }

                                $this->processObjectValidation(
                                    $xxx,
                                    $fieldName,
                                    (object)$nestedObject,
                                    $errors
                                );
                            } else {
                                $this->processValidation($xxx, $yy, (object)$nestedObject, $errors[$fieldName]);
                            }
                        }
                    }
                } else {
                    if (is_numeric($xFieldName)) { //multiple rules (?)
                        $xFieldName = $fieldName;
                        $nestedObject = $object;
                        $this->processValidation($xRule, $xFieldName, $nestedObject, $errors);
                    } else {
                        $nestedObject = $this->getFieldValue($fieldName, $object);
                        $errors[$fieldName] = [];
                        $this->processValidation($xRule, $xFieldName, (object)$nestedObject, $errors[$fieldName]);
                    }
                }
            }

            return;
        } else {
            $validator = $rule->getConstraint();
            if (in_array($fieldName, $this->invalidFields)) {
                return;
            }

            $value = $this->getFieldValue($fieldName, $object);
            if ($validator instanceof WholeObjectAware) {
                $result = $validator->validateRelated($value, $object);
            } else {
                $result = $validator->validate($value);
            }

            if ($result === false) {
                $this->invalidFields[] = $fieldName;
                $errors[$fieldName] = new Error($rule->getCode(), $validator->getMetadata());

            }
        }
    }

    private function processObjectValidation($rule, $fieldName, $object, &$errors): void
    {
        $validator = $rule->getConstraint();
        $result = $validator->validate($fieldName);

        if ($result === false) {
            $errors[$fieldName] = new Error($rule->getCode(), $validator->getMetadata());
        }
    }

    private function getFieldValue($fieldName, $object)
    {
        if ($object instanceof \stdClass) {
            if (isset($object->$fieldName)) {
                return $object->$fieldName;
            } else {
                return null;
            }
        }

        $reflection = new \ReflectionClass(get_class($object));
        try {
            $property = $reflection->getProperty($fieldName);
        }
        catch (\Exception $e) {
            $property = null;
        }

        $value = null;
        $methodName = 'get'.ucfirst($fieldName);

        if ($property && $property->isPublic()) {
            $value = $property->getValue($object);
        }
        elseif ($reflection->hasMethod($methodName)) {
            $value = call_user_func(array($object, $methodName));
        }

        return $value;
    }

    public function getErrors(?ErrorCodesTable $codesTable = null): array
    {
        $errors = $this->clearEmptyRecursively($this->errors);

        if (isset($codesTable)) {
            return $this->decorateErrors($errors, $codesTable);
        } else {
            return $errors;
        }
    }

    private function decorateErrors(array $errors, ErrorCodesTable $table): array
    {
        foreach ($errors as $key => $error) {
            if (is_array($error)) {
                $this->decorateErrors($error, $table);
            } else {
                /* @var $error \Ayeo\Alligator\Error */
                if ($table->has($error->getCode())) {
                    $error->setMessage($table->getMessage($error->getCode()));
                }
            }

        }

        return $errors;
    }

    private function clearEmptyRecursively(array $haystack): array
    {
        foreach ($haystack as $key => $value) {
            if (is_array($value)) {
                $haystack[$key] = $this->clearEmptyRecursively($haystack[$key]);
            }

            if (empty($haystack[$key])) {
                unset($haystack[$key]);
            }
        }

        return $haystack;
    }
}
