//
//  modelHttpBase.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-5.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "modelHttpBase.h"
#import "touchJson/JSON/CJSONDeserializer.h"

@implementation modelHttpBase


- (BOOL)doSynHttpGet:(NSString *)strurl
{
    NSURL *url = [NSURL URLWithString:strurl];
    ASIHTTPRequest *request = [ASIHTTPRequest requestWithURL:url];
    [request startSynchronous];
    
    NSError *error = [request error];
    if(!error) {
        NSString *response = [request responseString];
        return [self _checkRs:response];
    }
    else{
        return NO;
    }
}
- (BOOL)doSynHttpPost:(NSString *)url postData:(NSDictionary *)data
{
    ASIFormDataRequest *request = [[ASIFormDataRequest alloc] initWithURL:[NSURL URLWithString:url]];
    NSEnumerator *keys = [data keyEnumerator];
    for (NSObject *k in keys) {
        NSString *v = (NSString *)[data objectForKey:k];
        [request setPostValue:v forKey:k];
    }
    [request startSynchronous];
    NSError *error = [request error];
    if (!error) {
        NSString *response = [request responseString];
        return [self _checkRs:response];
    } else {
        return NO;
    }
}
- (void)saveLog:(NSString *)s
{
    NSLog(@"modelHttpBase %@" , s);
}
- (BOOL)_checkRs:(NSString *)rs
{
    [self log:rs];
    NSError *error = [[NSError alloc] init];
    NSDictionary *rsDic = [[CJSONDeserializer deserializer] deserialize:[rs dataUsingEncoding:NSUTF8StringEncoding] error:&error];
    NSString *code = [rsDic objectForKey:@"code"];

    if([code isEqualToString:@"A00006"])
    {
        self._dataDic = [rsDic objectForKey:@"data"];
        return YES;
    }
    else
    {
        self._error = [rsDic objectForKey:@"error"];
        return NO;
    }
}

@end
