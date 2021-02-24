<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: runtime/v1/dapr.proto

namespace Dapr\Proto\Runtime\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * GetSecretRequest is the message to get secret from secret store.
 *
 * Generated from protobuf message <code>dapr.proto.runtime.v1.GetSecretRequest</code>
 */
class GetSecretRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * The name of secret store.
     *
     * Generated from protobuf field <code>string store_name = 1;</code>
     */
    protected $store_name = '';
    /**
     * The name of secret key.
     *
     * Generated from protobuf field <code>string key = 2;</code>
     */
    protected $key = '';
    /**
     * The metadata which will be sent to secret store components.
     *
     * Generated from protobuf field <code>map<string, string> metadata = 3;</code>
     */
    private $metadata;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $store_name
     *           The name of secret store.
     *     @type string $key
     *           The name of secret key.
     *     @type array|\Google\Protobuf\Internal\MapField $metadata
     *           The metadata which will be sent to secret store components.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Runtime\V1\Dapr::initOnce();
        parent::__construct($data);
    }

    /**
     * The name of secret store.
     *
     * Generated from protobuf field <code>string store_name = 1;</code>
     * @return string
     */
    public function getStoreName()
    {
        return $this->store_name;
    }

    /**
     * The name of secret store.
     *
     * Generated from protobuf field <code>string store_name = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setStoreName($var)
    {
        GPBUtil::checkString($var, True);
        $this->store_name = $var;

        return $this;
    }

    /**
     * The name of secret key.
     *
     * Generated from protobuf field <code>string key = 2;</code>
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * The name of secret key.
     *
     * Generated from protobuf field <code>string key = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setKey($var)
    {
        GPBUtil::checkString($var, True);
        $this->key = $var;

        return $this;
    }

    /**
     * The metadata which will be sent to secret store components.
     *
     * Generated from protobuf field <code>map<string, string> metadata = 3;</code>
     * @return \Google\Protobuf\Internal\MapField
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * The metadata which will be sent to secret store components.
     *
     * Generated from protobuf field <code>map<string, string> metadata = 3;</code>
     * @param array|\Google\Protobuf\Internal\MapField $var
     * @return $this
     */
    public function setMetadata($var)
    {
        $arr = GPBUtil::checkMapField($var, \Google\Protobuf\Internal\GPBType::STRING, \Google\Protobuf\Internal\GPBType::STRING);
        $this->metadata = $arr;

        return $this;
    }

}
