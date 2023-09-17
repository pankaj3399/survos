<?php
/**
 * FrontendSettings
 *
 * PHP version 7.4
 *
 * @category Class
 * @package  Survos\LibreTranslateBundle
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * LibreTranslate
 *
 * No description provided (generated by Openapi Generator https://github.com/openapitools/openapi-generator)
 *
 * The version of the OpenAPI document: 1.3.11
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 6.6.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace Survos\LibreTranslateBundle\Model;

use \ArrayAccess;
use \Survos\LibreTranslateBundle\ObjectSerializer;

/**
 * FrontendSettings Class Doc Comment
 *
 * @category Class
 * @package  Survos\LibreTranslateBundle
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class FrontendSettings implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'frontend-settings';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'api_keys' => 'bool',
        'char_limit' => 'int',
        'frontend_timeout' => 'int',
        'key_required' => 'bool',
        'language' => '\Survos\LibreTranslateBundle\Model\FrontendSettingsLanguage',
        'suggestions' => 'bool',
        'supported_files_format' => 'string[]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'api_keys' => null,
        'char_limit' => null,
        'frontend_timeout' => null,
        'key_required' => null,
        'language' => null,
        'suggestions' => null,
        'supported_files_format' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'api_keys' => false,
		'char_limit' => false,
		'frontend_timeout' => false,
		'key_required' => false,
		'language' => false,
		'suggestions' => false,
		'supported_files_format' => false
    ];

    /**
      * If a nullable field gets set to null, insert it here
      *
      * @var boolean[]
      */
    protected array $openAPINullablesSetToNull = [];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
    }

    /**
     * Array of nullable properties
     *
     * @return array
     */
    protected static function openAPINullables(): array
    {
        return self::$openAPINullables;
    }

    /**
     * Array of nullable field names deliberately set to null
     *
     * @return boolean[]
     */
    private function getOpenAPINullablesSetToNull(): array
    {
        return $this->openAPINullablesSetToNull;
    }

    /**
     * Setter - Array of nullable field names deliberately set to null
     *
     * @param boolean[] $openAPINullablesSetToNull
     */
    private function setOpenAPINullablesSetToNull(array $openAPINullablesSetToNull): void
    {
        $this->openAPINullablesSetToNull = $openAPINullablesSetToNull;
    }

    /**
     * Checks if a property is nullable
     *
     * @param string $property
     * @return bool
     */
    public static function isNullable(string $property): bool
    {
        return self::openAPINullables()[$property] ?? false;
    }

    /**
     * Checks if a nullable property is set to null.
     *
     * @param string $property
     * @return bool
     */
    public function isNullableSetToNull(string $property): bool
    {
        return in_array($property, $this->getOpenAPINullablesSetToNull(), true);
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'api_keys' => 'apiKeys',
        'char_limit' => 'charLimit',
        'frontend_timeout' => 'frontendTimeout',
        'key_required' => 'keyRequired',
        'language' => 'language',
        'suggestions' => 'suggestions',
        'supported_files_format' => 'supportedFilesFormat'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'api_keys' => 'setApiKeys',
        'char_limit' => 'setCharLimit',
        'frontend_timeout' => 'setFrontendTimeout',
        'key_required' => 'setKeyRequired',
        'language' => 'setLanguage',
        'suggestions' => 'setSuggestions',
        'supported_files_format' => 'setSupportedFilesFormat'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'api_keys' => 'getApiKeys',
        'char_limit' => 'getCharLimit',
        'frontend_timeout' => 'getFrontendTimeout',
        'key_required' => 'getKeyRequired',
        'language' => 'getLanguage',
        'suggestions' => 'getSuggestions',
        'supported_files_format' => 'getSupportedFilesFormat'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$openAPIModelName;
    }


    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->setIfExists('api_keys', $data ?? [], null);
        $this->setIfExists('char_limit', $data ?? [], null);
        $this->setIfExists('frontend_timeout', $data ?? [], null);
        $this->setIfExists('key_required', $data ?? [], null);
        $this->setIfExists('language', $data ?? [], null);
        $this->setIfExists('suggestions', $data ?? [], null);
        $this->setIfExists('supported_files_format', $data ?? [], null);
    }

    /**
    * Sets $this->container[$variableName] to the given data or to the given default Value; if $variableName
    * is nullable and its value is set to null in the $fields array, then mark it as "set to null" in the
    * $this->openAPINullablesSetToNull array
    *
    * @param string $variableName
    * @param array  $fields
    * @param mixed  $defaultValue
    */
    private function setIfExists(string $variableName, array $fields, $defaultValue): void
    {
        if (self::isNullable($variableName) && array_key_exists($variableName, $fields) && is_null($fields[$variableName])) {
            $this->openAPINullablesSetToNull[] = $variableName;
        }

        $this->container[$variableName] = $fields[$variableName] ?? $defaultValue;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets api_keys
     *
     * @return bool|null
     */
    public function getApiKeys()
    {
        return $this->container['api_keys'];
    }

    /**
     * Sets api_keys
     *
     * @param bool|null $api_keys Whether the API key database is enabled.
     *
     * @return self
     */
    public function setApiKeys($api_keys)
    {
        if (is_null($api_keys)) {
            throw new \InvalidArgumentException('non-nullable api_keys cannot be null');
        }
        $this->container['api_keys'] = $api_keys;

        return $this;
    }

    /**
     * Gets char_limit
     *
     * @return int|null
     */
    public function getCharLimit()
    {
        return $this->container['char_limit'];
    }

    /**
     * Sets char_limit
     *
     * @param int|null $char_limit Character input limit for this language (-1 indicates no limit)
     *
     * @return self
     */
    public function setCharLimit($char_limit)
    {
        if (is_null($char_limit)) {
            throw new \InvalidArgumentException('non-nullable char_limit cannot be null');
        }
        $this->container['char_limit'] = $char_limit;

        return $this;
    }

    /**
     * Gets frontend_timeout
     *
     * @return int|null
     */
    public function getFrontendTimeout()
    {
        return $this->container['frontend_timeout'];
    }

    /**
     * Sets frontend_timeout
     *
     * @param int|null $frontend_timeout Frontend translation timeout
     *
     * @return self
     */
    public function setFrontendTimeout($frontend_timeout)
    {
        if (is_null($frontend_timeout)) {
            throw new \InvalidArgumentException('non-nullable frontend_timeout cannot be null');
        }
        $this->container['frontend_timeout'] = $frontend_timeout;

        return $this;
    }

    /**
     * Gets key_required
     *
     * @return bool|null
     */
    public function getKeyRequired()
    {
        return $this->container['key_required'];
    }

    /**
     * Sets key_required
     *
     * @param bool|null $key_required Whether an API key is required.
     *
     * @return self
     */
    public function setKeyRequired($key_required)
    {
        if (is_null($key_required)) {
            throw new \InvalidArgumentException('non-nullable key_required cannot be null');
        }
        $this->container['key_required'] = $key_required;

        return $this;
    }

    /**
     * Gets language
     *
     * @return \Survos\LibreTranslateBundle\Model\FrontendSettingsLanguage|null
     */
    public function getLanguage()
    {
        return $this->container['language'];
    }

    /**
     * Sets language
     *
     * @param \Survos\LibreTranslateBundle\Model\FrontendSettingsLanguage|null $language language
     *
     * @return self
     */
    public function setLanguage($language)
    {
        if (is_null($language)) {
            throw new \InvalidArgumentException('non-nullable language cannot be null');
        }
        $this->container['language'] = $language;

        return $this;
    }

    /**
     * Gets suggestions
     *
     * @return bool|null
     */
    public function getSuggestions()
    {
        return $this->container['suggestions'];
    }

    /**
     * Sets suggestions
     *
     * @param bool|null $suggestions Whether submitting suggestions is enabled.
     *
     * @return self
     */
    public function setSuggestions($suggestions)
    {
        if (is_null($suggestions)) {
            throw new \InvalidArgumentException('non-nullable suggestions cannot be null');
        }
        $this->container['suggestions'] = $suggestions;

        return $this;
    }

    /**
     * Gets supported_files_format
     *
     * @return string[]|null
     */
    public function getSupportedFilesFormat()
    {
        return $this->container['supported_files_format'];
    }

    /**
     * Sets supported_files_format
     *
     * @param string[]|null $supported_files_format Supported files format
     *
     * @return self
     */
    public function setSupportedFilesFormat($supported_files_format)
    {
        if (is_null($supported_files_format)) {
            throw new \InvalidArgumentException('non-nullable supported_files_format cannot be null');
        }
        $this->container['supported_files_format'] = $supported_files_format;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed|null
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->container[$offset] ?? null;
    }

    /**
     * Sets value based on offset.
     *
     * @param int|null $offset Offset
     * @param mixed    $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }

    /**
     * Serializes the object to a value that can be serialized natively by json_encode().
     * @link https://www.php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed Returns data which can be serialized by json_encode(), which is a value
     * of any type other than a resource.
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
       return ObjectSerializer::sanitizeForSerialization($this);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            ObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }

    /**
     * Gets a header-safe presentation of the object
     *
     * @return string
     */
    public function toHeaderValue()
    {
        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}


