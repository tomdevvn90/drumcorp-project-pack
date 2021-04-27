<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Taskrouter\V1\Workspace;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Options;
use Twilio\Values;
use Twilio\Version;

/**
 * @property array $realtime
 * @property array $cumulative
 * @property string $accountSid
 * @property string $workspaceSid
 * @property string $url
 */
class WorkspaceStatisticsInstance extends InstanceResource {
    /**
     * Initialize the WorkspaceStatisticsInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $workspaceSid The SID of the Workspace
     */
    public function __construct(Version $version, array $payload, string $workspaceSid) {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = [
            'realtime' => Values::array_get($payload, 'realtime'),
            'cumulative' => Values::array_get($payload, 'cumulative'),
            'accountSid' => Values::array_get($payload, 'account_sid'),
            'workspaceSid' => Values::array_get($payload, 'workspace_sid'),
            'url' => Values::array_get($payload, 'url'),
        ];

        $this->solution = ['workspaceSid' => $workspaceSid, ];
    }

    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return WorkspaceStatisticsContext Context for this
     *                                    WorkspaceStatisticsInstance
     */
    protected function proxy(): WorkspaceStatisticsContext {
        if (!$this->context) {
            $this->context = new WorkspaceStatisticsContext($this->version, $this->solution['workspaceSid']);
        }

        return $this->context;
    }

    /**
     * Fetch the WorkspaceStatisticsInstance
     *
     * @param array|Options $options Optional Arguments
     * @return WorkspaceStatisticsInstance Fetched WorkspaceStatisticsInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(array $options = []): WorkspaceStatisticsInstance {
        return $this->proxy()->fetch($options);
    }

    /**
     * Magic getter to access properties
     *
     * @param string $name Property to access
     * @return mixed The requested property
     * @throws TwilioException For unknown properties
     */
    public function __get(string $name) {
        if (\array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }

        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown property: ' . $name);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Taskrouter.V1.WorkspaceStatisticsInstance ' . \implode(' ', $context) . ']';
    }
}