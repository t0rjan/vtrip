//
//  modelHttpBase.h
//  trip
//
//  Created by 沈 吾苓 on 13-2-5.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "modelBase.h"
#import "ASIHTTPRequest.h"
#import "ASIFormDataRequest.h"



#define ML_APPCODE_OK "A00006";

@interface modelHttpBase : modelBase


- (BOOL)doSynHttpGet:(NSString *)url;
- (BOOL)doSynHttpPost:(NSString *)url postData:(NSDictionary *)data;
- (void)saveLog:(NSString *)s;


@end
