<?php
/**
 * SuggestResponse
 *
 * PHP version 8.1.1
 *
 * @category Class
 * @package  OpenAPI\Server\Model
 * @author   OpenAPI Generator team
 * @link     https://github.com/openapitools/openapi-generator
 */

/**
 * LibreTranslate
 *
 * No description provided (generated by Openapi Generator https://github.com/openapitools/openapi-generator)
 *
 * The version of the OpenAPI document: 1.3.11
 * 
 * Generated by: https://github.com/openapitools/openapi-generator.git
 *
 */

/**
 * NOTE: This class is auto generated by the openapi generator program.
 * https://github.com/openapitools/openapi-generator
 * Do not edit the class manually.
 */

namespace OpenAPI\Server\Model;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class representing the SuggestResponse model.
 *
 * @package OpenAPI\Server\Model
 * @author  OpenAPI Generator team
 */

class SuggestResponse 
{
        /**
     * Whether submission was successful
     *
     * @var bool|null
     * @SerializedName("success")
     * @Assert\Type("bool")
     * @Type("bool")
     */
    protected ?bool $success = null;

    /**
     * Constructor
     * @param array|null $data Associated array of property values initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->success = $data['success'] ?? null;
    }

    /**
     * Gets success.
     *
     * @return bool|null
     */
    public function isSuccess(): ?bool
    {
        return $this->success;
    }

    /**
     * Sets success.
     *
     * @param bool|null $success  Whether submission was successful
     *
     * @return $this
     */
    public function setSuccess(?bool $success = null): self
    {
        $this->success = $success;

        return $this;
    }
}


