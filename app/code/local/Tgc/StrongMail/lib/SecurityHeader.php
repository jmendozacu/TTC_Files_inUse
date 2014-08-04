<?php
/*
 * -------------------------------------------------------------------- *
 *  STRONGMAIL SYSTEMS                                                  *
 *                                                                      *
 *  Copyright 2010 StrongMail Systems, Inc. - All rights reserved.      *
 *                                                                      *
 *  Visit http://www.strongmail.com for more information                *
 *                                                                      *
 *  You may incorporate this Source Code in your application only if    *
 *  you own a valid license to do so from StrongMail Systems, Inc.      *
 *  and the copyright notices are not removed from the source code.     *
 *                                                                      *
 *  Distributing our source code outside your organization              *
 *  is strictly prohibited                                              *
 *                                                                      *
 * -------------------------------------------------------------------- *
 */

class Tgc_StrongMail_SecurityHeader
{
  private $passwordTextType = "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText";
  private $securityNamespace = "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd";
  private $securityHeaderElementName = "Security";
  private $schemaVersion = "http://www.strongmail.com/services/2009/03/02/schema";
  private $securityHeaderMustUnderstand = true;

  private $template = '<wsse:Security xmlns:wsse="%s" SOAP-ENV:mustUnderstand="1">
    <wsse:UsernameToken xmlns:wsse="%s">
      <wsse:Username xmlns:wsse="%s">%s</wsse:Username>
      <wsse:Password xmlns:wsse="%s" Type="%s">%s</wsse:Password>
    </wsse:UsernameToken>
    <OrganizationToken xmlns="%s">
      <organizationName>%s</organizationName>
      %s
    </OrganizationToken>
    %s
  </wsse:Security>';
  private $subOrgTemplate = '<subOrganizationId><id>%s</id></subOrganizationId>';
  private $isSSOTemplate = '<IsSSO xmlns="%s"></IsSSO>';

  /**
   * You MUST fill in a value for subOrganizationId if you are using a
   * sub-organization.  The value should be a string containing the
   * numerical id of that organization, i.e. "201".  If you are
   * using a top-level organization, such as "admin", you may leave
   * it out.
  */
  public function setSecurityHeader($service, $username, $password, $organizationName, $subOrganizationId=null, $isSSO=null)
  {
    $securityHeaderText = sprintf($this->template,
                              $this->securityNamespace,
                              $this->securityNamespace,
                              $this->securityNamespace,
                              $username,
                              $this->securityNamespace,
                              $this->passwordTextType,
                              $password,
                              $this->schemaVersion,
                              $organizationName,
                              (isset($subOrganizationId)? sprintf($this->subOrgTemplate, $subOrganizationId) : ""),
                              ((isset($isSSO) && $isSSO) ? sprintf($this->isSSOTemplate, $this->schemaVersion) : "")
                              );
    $securityHeaderSoapVar = new SoapVar($securityHeaderText, XSD_ANYXML, null, null, null);
    $securityHeader = new SoapHeader($this->securityNamespace,
                                   $this->securityHeaderElementName,
                                   $securityHeaderSoapVar,
                                   $this->securityHeaderMustUnderstand);

    $service->__setSOAPHeaders(array($securityHeader));
  }
}
